<?php

namespace App\Repositories\Employee;

use App\Contracts\Employee\LeaveRequestInterface;
use App\Models\Employee\LeaveRequest;

class LeaveRequestRepository implements LeaveRequestInterface
{
    public function findWithRelations($id)
    {
        return LeaveRequest::with(['leaveType', 'user.manager', 'user'])->find($id);
    }

    public function findByUserWithStatus($userId, $status = null, $perPage = 10, $page = 1)
    {
        $query = LeaveRequest::with(['leaveType', 'user.manager', 'user'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function findByManagerWithStatus($managerId, $status = null, $perPage = 10, $page = 1)
    {
        $query = LeaveRequest::with(['leaveType', 'user.manager', 'user'])
            ->whereHas('user.manager', fn($q) => $q->where('id', $managerId))
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function updateStatus($leaveRequest, $status)
    {
        return $leaveRequest->update(['status' => $status]);
    }

    public function isApprovable($leaveRequest, $approverId)
    {
        return $leaveRequest
            && $leaveRequest->user_id
            && $leaveRequest->user->manager_id === $approverId
            && !in_array($leaveRequest->status, ['APPROVED', 'REJECTED', 'CANCELLED']);
    }
}