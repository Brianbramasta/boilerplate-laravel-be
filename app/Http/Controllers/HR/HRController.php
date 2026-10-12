<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\BaseController;
use App\Models\Employee\LeaveRequest;
use Illuminate\Http\Request;

class HRController extends BaseController
{
    protected function ensureHR()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'hr') {
            return $this->errorResponse('Forbidden. Anda tidak memiliki akses ke modul ini.', 403);
        }
        return null;
    }

    public function leaveRequests(Request $request)
    {
        $guard = $this->ensureHR();
        if ($guard) return $guard;

        $query = LeaveRequest::with(['leaveType', 'user.manager']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('start_date', [$request->get('start_date'), $request->get('end_date')]);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $requests = $query->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse(
            data: [
                'current_page' => $requests->currentPage(),
                'data' => collect($requests->items())->map(fn($r) => [
                    'id' => $r->id,
                    'employee_name' => $r->user->name,
                    'department' => 'Engineering',
                    'leave_type_name' => $r->leaveType->name,
                    'start_date' => $r->start_date->format('Y-m-d'),
                    'end_date' => $r->end_date->format('Y-m-d'),
                    'status' => $r->status,
                    'created_at' => $r->created_at->toIso8601String(),
                ]),
                'per_page' => $requests->perPage(),
                'to' => $requests->lastItem(),
                'total' => $requests->total(),
            ],
            message: 'Daftar seluruh pengajuan cuti berhasil diambil'
        );
    }

    public function leaveRequestDetail($id)
    {
        $guard = $this->ensureHR();
        if ($guard) return $guard;

        $request = LeaveRequest::with(['leaveType', 'user', 'approvalHistories'])->find($id);

        if (!$request) {
            return $this->errorResponse('Data pengajuan cuti tidak ditemukan.', 404);
        }

        return $this->successResponse(
            data: [
                'id' => $request->id,
                'employee_name' => $request->user->name,
                'leave_type_name' => $request->leaveType->name,
                'start_date' => $request->start_date->format('Y-m-d'),
                'end_date' => $request->end_date->format('Y-m-d'),
                'total_days' => $request->total_days,
                'reason' => $request->reason,
                'status' => $request->status,
                'approval_histories' => $request->approvalHistories->map(fn($h) => [
                    'actor_name' => $h->actor_name,
                    'previous_status' => $h->previous_status,
                    'next_status' => $h->next_status,
                    'notes' => $h->notes,
                    'created_at' => $h->created_at->toIso8601String(),
                ]),
            ],
            message: 'Detail pengajuan cuti berhasil diambil'
        );
    }

    public function leaveSummaryReport(Request $request)
    {
        $guard = $this->ensureHR();
        if ($guard) return $guard;

        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfYear()->format('Y-m-d'));

        $totalRequests = LeaveRequest::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalApproved = LeaveRequest::whereBetween('created_at', [$startDate, $endDate])->where('status', 'APPROVED')->count();
        $totalRejected = LeaveRequest::whereBetween('created_at', [$startDate, $endDate])->where('status', 'REJECTED')->count();
        $totalCancelled = LeaveRequest::whereBetween('created_at', [$startDate, $endDate])->where('status', 'CANCELLED')->count();

        return $this->successResponse(
            data: [
                'period_start' => $startDate,
                'period_end' => $endDate,
                'total_requests' => $totalRequests,
                'total_approved' => $totalApproved,
                'total_rejected' => $totalRejected,
                'total_cancelled' => $totalCancelled,
            ],
            message: 'Laporan rekapitulasi cuti berhasil diambil'
        );
    }

    public function usageByTypeReport(Request $request)
    {
        $guard = $this->ensureHR();
        if ($guard) return $guard;

        $year = $request->get('year', now()->year);

        $data = LeaveRequest::where('status', 'APPROVED')
            ->whereYear('created_at', $year)
            ->with('leaveType')
            ->get()
            ->groupBy('leave_type_id')
            ->map(fn($requests, $typeId) => [
                'leave_type_name' => $requests->first()->leaveType->name,
                'total_requests' => $requests->count(),
                'total_days_used' => $requests->sum('total_days'),
            ])
            ->values();

        return $this->successResponse(
            data: $data,
            message: 'Laporan penggunaan berdasarkan jenis cuti berhasil diambil'
        );
    }

    public function usageByEmployeeReport(Request $request)
    {
        $guard = $this->ensureHR();
        if ($guard) return $guard;

        $year = $request->get('year', now()->year);
        $search = $request->get('search');

        $query = \App\Models\User::with(['leaveRequests' => fn($q) => $q->where('status', 'APPROVED')->whereYear('created_at', $year)]);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $employees = $query->paginate($perPage, ['*'], 'page', $page);

        $data = collect($employees->items())->map(fn($user) => [
            'employee_id' => $user->id,
            'employee_name' => $user->name,
            'department' => 'Engineering',
            'total_requests' => $user->leaveRequests->count(),
            'total_days_used' => $user->leaveRequests->sum('total_days'),
            'leave_details' => $user->leaveRequests->groupBy('leave_type_id')->map(fn($reqs) => [
                'leave_type_name' => $reqs->first()->leaveType->name,
                'days_used' => $reqs->sum('total_days'),
            ])->values(),
        ]);

        return $this->successResponse(
            data: [
                'current_page' => $employees->currentPage(),
                'data' => $data,
                'per_page' => $employees->perPage(),
                'to' => $employees->lastItem(),
                'total' => $employees->total(),
            ],
            message: 'Laporan penggunaan cuti berdasarkan karyawan berhasil diambil'
        );
    }
}