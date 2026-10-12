<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Approver\ApproveLeaveRequest;
use App\Http\Requests\Approver\RejectLeaveRequest;
use App\Models\Employee\ApprovalHistory;
use App\Models\Employee\LeaveBalance;
use App\Models\Employee\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApproverController extends BaseController
{
    protected function ensureApprover()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['approver', 'hr', 'admin'])) {
            return $this->errorResponse('Anda tidak memiliki akses untuk menyetujui cuti.', 403);
        }
        return null;
    }

    public function leaveRequests(Request $request)
    {
        $guard = $this->ensureApprover();
        if ($guard) return $guard;

        $user = auth()->user();

        $query = LeaveRequest::with(['leaveType', 'user.manager', 'user'])
            ->whereHas('user.manager', fn($q) => $q->where('id', $user->id));

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $requests = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse(
            data: [
                'current_page' => $requests->currentPage(),
                'data' => collect($requests->items())->map(fn($r) => [
                    'id' => $r->id,
                    'employee_name' => $r->user->name,
                    'leave_type_name' => $r->leaveType->name,
                    'start_date' => $r->start_date->format('Y-m-d'),
                    'end_date' => $r->end_date->format('Y-m-d'),
                    'total_days' => $r->total_days,
                    'reason' => $r->reason,
                    'status' => $r->status,
                    'created_at' => $r->created_at->toIso8601String(),
                ]),
                'per_page' => $requests->perPage(),
                'to' => $requests->lastItem(),
                'total' => $requests->total(),
            ],
            message: 'Daftar pengajuan cuti bawahan berhasil diambil'
        );
    }

    public function approve(ApproveLeaveRequest $request, $id)
    {
        $guard = $this->ensureApprover();
        if ($guard) return $guard;

        $leaveRequest = LeaveRequest::where('id', $id)
            ->whereHas('user.manager', fn($q) => $q->where('id', auth()->id()))
            ->first();

        if (!$leaveRequest) {
            return $this->errorResponse('Data pengajuan cuti tidak ditemukan atau Anda tidak memiliki akses.', 404);
        }

        if (in_array($leaveRequest->status, ['APPROVED', 'REJECTED', 'CANCELLED'])) {
            return $this->errorResponse("Pengajuan tidak dapat disetujui karena status saat ini sudah {$leaveRequest->status}.", 400);
        }

        $year = $leaveRequest->start_date->year;
        $balance = LeaveBalance::where('user_id', $leaveRequest->user_id)
            ->where('leave_type_id', $leaveRequest->leave_type_id)
            ->where('year', $year)
            ->first();

        if (!$balance || $balance->remaining_balance < $leaveRequest->total_days) {
            return $this->errorResponse('Gagal menyetujui. Saldo cuti karyawan tidak mencukupi.', 400);
        }

        try {
            DB::transaction(function () use ($leaveRequest, $balance, $request) {
                $leaveRequest->update(['status' => 'APPROVED']);

                $balance->decrement('used_quota', $leaveRequest->total_days);
                $balance->decrement('remaining_balance', $leaveRequest->total_days);

                ApprovalHistory::create([
                    'leave_request_id' => $leaveRequest->id,
                    'actor_id' => auth()->id(),
                    'actor_name' => auth()->user()->name,
                    'previous_status' => $leaveRequest->status,
                    'next_status' => 'APPROVED',
                    'notes' => $request->get('notes'),
                ]);
            });

            return $this->successResponse(
                data: [
                    'id' => $leaveRequest->id,
                    'status' => 'APPROVED',
                ],
                message: 'Pengajuan cuti berhasil disetujui'
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Gagal menyetujui pengajuan cuti.', 500);
        }
    }

    public function reject(RejectLeaveRequest $request, $id)
    {
        $guard = $this->ensureApprover();
        if ($guard) return $guard;

        $leaveRequest = LeaveRequest::where('id', $id)
            ->whereHas('user.manager', fn($q) => $q->where('id', auth()->id()))
            ->first();

        if (!$leaveRequest) {
            return $this->errorResponse('Data pengajuan cuti tidak ditemukan atau Anda tidak memiliki akses.', 404);
        }

        if (in_array($leaveRequest->status, ['APPROVED', 'REJECTED', 'CANCELLED'])) {
            return $this->errorResponse("Pengajuan tidak dapat ditolak karena status saat ini sudah {$leaveRequest->status}.", 400);
        }

        $leaveRequest->update(['status' => 'REJECTED']);

        ApprovalHistory::create([
            'leave_request_id' => $leaveRequest->id,
            'actor_id' => auth()->id(),
            'actor_name' => auth()->user()->name,
            'previous_status' => $leaveRequest->status,
            'next_status' => 'REJECTED',
            'notes' => $request->get('notes'),
        ]);

        return $this->successResponse(
            data: [
                'id' => $leaveRequest->id,
                'status' => 'REJECTED',
            ],
            message: 'Pengajuan cuti berhasil ditolak'
        );
    }
}