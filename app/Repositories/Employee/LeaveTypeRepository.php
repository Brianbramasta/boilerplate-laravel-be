<?php

namespace App\Repositories\Employee;

use App\Contracts\Employee\LeaveTypeInterface;
use App\Models\Employee\LeaveType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveTypeRepository implements LeaveTypeInterface
{
    public function query()
    {
        return LeaveType::query();
    }

    public function paginate($perPage = 10, $page = 1)
    {
        return LeaveType::query()->paginate($perPage, ['*'], 'page', $page);
    }
}