<?php

namespace App\Contracts\Employee;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeaveTypeInterface
{
    public function query();
    public function paginate($perPage = 10, $page = 1);
}