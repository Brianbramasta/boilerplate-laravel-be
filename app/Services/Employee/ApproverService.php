<?php

namespace App\Services\Employee;

use App\Contracts\Employee\ApprovalHistoryInterface;
use App\Contracts\Employee\LeaveBalanceInterface;
use App\Contracts\Employee\LeaveRequestInterface;
use App\Models\Employee\LeaveRequest;
use Illuminate\Support\Facades\DB;

class ApproverService
{
    protected $leaveRequestRepo;
    protected $leaveBalanceRepo;
    protected $approvalHistoryRepo;

    public function __construct(
        LeaveRequestInterface $leaveRequestRepo,
        LeaveBalanceInterface $leaveBalanceRepo,
        ApprovalHistoryInterface $approvalHistoryRepo
    ) {
        $this->leaveRequestRepo = $leaveRequestRepo;
        $this->leaveBalanceRepo = $leaveBalanceRepo;
        $this->approvalHistoryRepo = $approvalHistoryRepo;
    }

    public function getLeaveRequestsByManager($manager, $status = null, $page = 1, $perPage = 10)
    {
        return $this->leaveRequestRepo->findByManagerWithStatus($manager->id, $status, $perPage, $page);
    }

    public function approve($leaveRequestId, $approverId, $notes = null)
    {
        $leaveRequest = $this->leaveRequestRepo->findWithRelations($leaveRequestId);

        if (!$this->leaveRequestRepo->isApprovable($leaveRequest, $approverId)) {
            return null;
        }

        $year = $leaveRequest->start_date->year;
        $balance = $this->leaveBalanceRepo->findByUserAndTypeAndYear(
            $leaveRequest->user_id,
            $leaveRequest->leave_type_id,
            $year
        );

        if (!$this->leaveBalanceRepo->hasSufficientBalance($balance, $leaveRequest->total_days)) {
            return null;
        }

        DB::transaction(function () use ($leaveRequest, $balance, $approverId, $notes) {
            $previousStatus = $leaveRequest->status;
            $this->leaveRequestRepo->updateStatus($leaveRequest, LeaveRequest::STATUS_APPROVED);
            $this->leaveBalanceRepo->decrementBalance($balance, $leaveRequest->total_days);

            $this->approvalHistoryRepo->create([
                'leave_request_id' => $leaveRequest->id,
                'actor_id' => $approverId,
                'actor_name' => auth()->user()->name,
                'previous_status' => $previousStatus,
                'next_status' => LeaveRequest::STATUS_APPROVED,
                'notes' => $notes,
            ]);
        });

        return $this->leaveRequestRepo->findWithRelations($leaveRequestId);
    }

    public function reject($leaveRequestId, $approverId, $notes = null)
    {
        $leaveRequest = $this->leaveRequestRepo->findWithRelations($leaveRequestId);

        if (!$this->leaveRequestRepo->isApprovable($leaveRequest, $approverId)) {
            return null;
        }

        DB::transaction(function () use ($leaveRequest, $approverId, $notes) {
            $previousStatus = $leaveRequest->status;
            $this->leaveRequestRepo->updateStatus($leaveRequest, LeaveRequest::STATUS_REJECTED);

            $this->approvalHistoryRepo->create([
                'leave_request_id' => $leaveRequest->id,
                'actor_id' => $approverId,
                'actor_name' => auth()->user()->name,
                'previous_status' => $previousStatus,
                'next_status' => LeaveRequest::STATUS_REJECTED,
                'notes' => $notes,
            ]);
        });

        return $this->leaveRequestRepo->findWithRelations($leaveRequestId);
    }
}