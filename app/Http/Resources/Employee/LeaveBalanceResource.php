<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveBalanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'leave_type_id' => $this->leave_type_id,
            'leave_type_name' => $this->leaveType->name ?? null,
            'year' => $this->year,
            'total_quota' => $this->total_quota,
            'used_quota' => $this->used_quota,
            'remaining_balance' => $this->remaining_balance,
        ];
    }
}