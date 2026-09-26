<?php

namespace Database\Factories;

use App\Models\Employee\LeaveBalance;
use App\Models\Employee\LeaveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveBalanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'leave_type_id' => LeaveType::factory(),
            'year' => now()->year,
            'total_quota' => 12,
            'used_quota' => 0,
            'remaining_balance' => 12,
        ];
    }
}