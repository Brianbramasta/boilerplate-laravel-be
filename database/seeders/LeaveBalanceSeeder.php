<?php

namespace Database\Seeders;

use App\Models\Employee\LeaveBalance;
use App\Models\Employee\LeaveType;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;

        $employees = User::where('role', 'employee')->get();
        $leaveTypes = LeaveType::all();

        foreach ($employees as $employee) {
            foreach ($leaveTypes as $leaveType) {
                $totalQuota = match($leaveType->code) {
                    'ANNUAL' => 12,
                    'SICK' => 14,
                    'SPECIAL' => 5,
                    default => 10,
                };

                LeaveBalance::create([
                    'user_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => $year,
                    'total_quota' => $totalQuota,
                    'used_quota' => 0,
                    'remaining_balance' => $totalQuota,
                ]);
            }
        }
    }
}