<?php

namespace App\Repositories\Employee;

use App\Contracts\Employee\LeaveBalanceInterface;
use App\Models\Employee\LeaveBalance;

class LeaveBalanceRepository implements LeaveBalanceInterface
{
    public function findByUserAndTypeAndYear($userId, $leaveTypeId, $year)
    {
        return LeaveBalance::where('user_id', $userId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->first();
    }

    public function findAllByUser($userId, $perPage = 10, $page = 1)
    {
        return LeaveBalance::where('user_id', $userId)
            ->with('leaveType')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function hasSufficientBalance($balance, $totalDays)
    {
        return $balance && $balance->remaining_balance >= $totalDays;
    }

    public function decrementBalance($balance, $days)
    {
        $balance->decrement('used_quota', $days);
        $balance->decrement('remaining_balance', $days);
    }
}