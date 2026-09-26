<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@dasaria.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'manager_id' => null,
        ]);

        User::create([
            'name' => 'HR Manager',
            'email' => 'hr@dasaria.com',
            'password' => Hash::make('password123'),
            'role' => 'hr',
            'manager_id' => null,
        ]);

        $approver = User::create([
            'name' => 'John Smith',
            'email' => 'john.smith@dasaria.com',
            'password' => Hash::make('password123'),
            'role' => 'approver',
            'manager_id' => null,
        ]);

        User::create([
            'name' => 'Jane Doe',
            'email' => 'jane.doe@dasaria.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'manager_id' => $approver->id,
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob.wilson@dasaria.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'manager_id' => $approver->id,
        ]);
    }
}