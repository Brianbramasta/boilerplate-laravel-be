<?php

namespace App\Contracts\Employee;

interface LeaveBalanceInterface
{
    public function findByUserAndTypeAndYear($userId, $leaveTypeId, $year);
    public function hasSufficientBalance($balance, $totalDays);
    public function decrementBalance($balance, $days);
}