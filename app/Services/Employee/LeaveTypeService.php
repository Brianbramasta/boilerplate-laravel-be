<?php

namespace App\Services\Employee;

use App\Contracts\Employee\LeaveTypeInterface;

class LeaveTypeService
{
    protected $leaveTypeRepo;

    public function __construct(LeaveTypeInterface $leaveTypeRepo)
    {
        $this->leaveTypeRepo = $leaveTypeRepo;
    }

    public function getPaginated($perPage = 10, $page = 1)
    {
        return $this->leaveTypeRepo->paginate($perPage, $page);
    }
}