<?php

namespace App\Contracts\Employee;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeaveRequestInterface
{
    public function findWithRelations($id);
    public function findByUserWithStatus($userId, $status = null, $perPage = 10, $page = 1);
    public function findByManagerWithStatus($managerId, $status = null, $perPage = 10, $page = 1);
    public function updateStatus($leaveRequest, $status);
    public function isApprovable($leaveRequest, $approverId);
}