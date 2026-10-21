<?php

namespace App\Services\Employee;

use App\Contracts\Employee\LeaveBalanceInterface;
use App\Contracts\Employee\LeaveRequestInterface;
use App\Models\Employee\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeService
{
    protected $leaveRequestRepo;
    protected $leaveBalanceRepo;

    public function __construct(
        LeaveRequestInterface $leaveRequestRepo,
        LeaveBalanceInterface $leaveBalanceRepo
    ) {
        $this->leaveRequestRepo = $leaveRequestRepo;
        $this->leaveBalanceRepo = $leaveBalanceRepo;
    }

    public function createLeaveRequest(array $data, int $userId): LeaveRequest
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $balance = $this->leaveBalanceRepo->findByUserAndTypeAndYear(
            $userId,
            $data['leave_type_id'],
            $startDate->year
        );

        if (!$balance) {
            throw new HttpException(400, 'Saldo cuti tidak ditemukan.');
        }

        if ($totalDays > $balance->remaining_balance) {
            throw new HttpException(400, 'Saldo cuti tidak mencukupi.');
        }

        if ($this->hasOverlap($userId, $data['leave_type_id'], $startDate, $endDate)) {
            throw new HttpException(422, 'Periode cuti bentrok dengan pengajuan Anda yang lain.');
        }

        return DB::transaction(function () use ($data, $userId, $totalDays) {
            return LeaveRequest::create([
                'user_id' => $userId,
                'leave_type_id' => $data['leave_type_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'total_days' => $totalDays,
                'reason' => $data['reason'],
                'status' => LeaveRequest::STATUS_SUBMITTED,
            ]);
        });
    }

    public function getLeaveRequestsByUser($userId, $status = null, $perPage = 10, $page = 1)
    {
        return $this->leaveRequestRepo->findByUserWithStatus($userId, $status, $perPage, $page);
    }

    public function cancelLeaveRequest($id, $userId)
    {
        $leaveRequest = LeaveRequest::where('user_id', $userId)->where('id', $id)->first();

        if (!$leaveRequest) {
            return null;
        }

        if (!$leaveRequest->isCancellable()) {
            return false;
        }

        DB::transaction(function () use ($leaveRequest) {
            $leaveRequest->update(['status' => LeaveRequest::STATUS_CANCELLED]);
        });

        return $leaveRequest;
    }

    public function getLeaveBalances($userId, $perPage = 10, $page = 1)
    {
        return $this->leaveBalanceRepo->findAllByUser($userId, $perPage, $page);
    }

    private function hasOverlap(int $userId, int $leaveTypeId, Carbon $startDate, Carbon $endDate): bool
    {
        return LeaveRequest::where('user_id', $userId)
            ->where('status', '!=', LeaveRequest::STATUS_CANCELLED)
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->exists();
    }
}