<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Employee\CreateLeaveRequest;
use App\Http\Resources\Employee\LeaveTypeResource;
use App\Http\Resources\Employee\LeaveBalanceResource;
use App\Http\Resources\Employee\LeaveRequestResource;
use App\Models\Employee\LeaveBalance;
use App\Models\Employee\LeaveRequest;
use App\Models\Employee\LeaveType;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{
    public function leaveTypes(Request $request)
    {
        $query = LeaveType::query();

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $leaveTypes = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse(
            data: [
                'current_page' => $leaveTypes->currentPage(),
                'data' => LeaveTypeResource::collection($leaveTypes->items()),
                'per_page' => $leaveTypes->perPage(),
                'to' => $leaveTypes->lastItem(),
                'total' => $leaveTypes->total(),
            ],
            message: 'Daftar jenis cuti berhasil diambil',
            code: 200
        );
    }

    public function leaveBalances(Request $request)
    {
        $user = auth()->user();

        $query = LeaveBalance::where('user_id', $user->id);

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $balances = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse(
            data: [
                'current_page' => $balances->currentPage(),
                'data' => LeaveBalanceResource::collection($balances->items()),
                'per_page' => $balances->perPage(),
                'to' => $balances->lastItem(),
                'total' => $balances->total(),
            ],
            message: 'Saldo cuti berhasil diambil',
            code: 200
        );
    }

    public function leaveRequests(Request $request)
    {
        $user = auth()->user();

        $query = LeaveRequest::where('user_id', $user->id);

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $requests = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse(
            data: [
                'current_page' => $requests->currentPage(),
                'data' => LeaveRequestResource::collection($requests->items()),
                'per_page' => $requests->perPage(),
                'to' => $requests->lastItem(),
                'total' => $requests->total(),
            ],
            message: 'Daftar pengajuan cuti berhasil diambil',
            code: 200
        );
    }

    public function createLeaveRequest(CreateLeaveRequest $request)
    {
        $data = $request->validated();

        $leaveRequest = LeaveRequest::create($data);

        return $this->successResponse(
            data: [
                'id' => $leaveRequest->id,
                'leave_type_id' => $leaveRequest->leave_type_id,
                'start_date' => $leaveRequest->start_date,
                'end_date' => $leaveRequest->end_date,
                'total_days' => $leaveRequest->total_days,
                'reason' => $leaveRequest->reason,
                'status' => $leaveRequest->status,
            ],
            message: 'Pengajuan cuti berhasil disubmit',
            code: 201
        );
    }

    public function cancelLeaveRequest($id)
    {
        $user = auth()->user();

        $leaveRequest = LeaveRequest::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$leaveRequest) {
            return $this->errorResponse('Data pengajuan cuti tidak ditemukan.', 404);
        }

        if (!$leaveRequest->isCancellable()) {
            $status = match($leaveRequest->status) {
                'APPROVED' => 'APPROVED',
                'REJECTED' => 'REJECTED',
                'CANCELLED' => 'CANCELLED',
                default => 'STATUS'
            };
            return $this->errorResponse("Pengajuan tidak dapat dibatalkan karena status saat ini adalah {$status}.", 400);
        }

        $leaveRequest->update(['status' => LeaveRequest::STATUS_CANCELLED]);

        return $this->successResponse(
            data: [
                'id' => $leaveRequest->id,
                'status' => $leaveRequest->status,
            ],
            message: 'Pengajuan cuti berhasil dibatalkan',
            code: 200
        );
    }
}