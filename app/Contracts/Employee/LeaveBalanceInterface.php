<?php

namespace App\Contracts\Employee;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeaveBalanceInterface
{
    public function findByUserAndTypeAndYear($userId, $leaveTypeId, $year);
    public function findAllByUser($userId, $perPage = 10, $page = 1);
    public function hasSufficientBalance($balance, $totalDays);
    public function decrementBalance($balance, $days);
}