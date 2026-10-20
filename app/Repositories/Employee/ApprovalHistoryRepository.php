<?php

namespace App\Repositories\Employee;

use App\Contracts\Employee\ApprovalHistoryInterface;
use App\Models\Employee\ApprovalHistory;

class ApprovalHistoryRepository implements ApprovalHistoryInterface
{
    public function create($data)
    {
        return ApprovalHistory::create($data);
    }
}