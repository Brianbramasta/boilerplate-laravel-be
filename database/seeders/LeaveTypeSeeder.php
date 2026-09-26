<?php

namespace Database\Seeders;

use App\Models\Employee\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        LeaveType::create([
            'code' => 'ANNUAL',
            'name' => 'Cuti Tahunan',
            'is_deduct_balance' => true,
        ]);

        LeaveType::create([
            'code' => 'SICK',
            'name' => 'Cuti Sakit',
            'is_deduct_balance' => true,
        ]);

        LeaveType::create([
            'code' => 'SPECIAL',
            'name' => 'Cuti Khusus',
            'is_deduct_balance' => true,
        ]);
    }
}