<?php

namespace Database\Seeders;

use App\Models\RolePermission\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $it = Role::where('slug', 'it')->firstOrFail();
        $superadmin = Role::where('slug', 'superadmin')->firstOrFail();
        $adminCuni = Role::where('slug', 'admin-cuni')->firstOrFail();

        $users = [
            [
                'email' => 'it@example.com',
                'name' => 'IT User',
                'role_id' => $it->id,
            ],
            [
                'email' => 'superadmin@example.com',
                'name' => 'Superadmin User',
                'role_id' => $superadmin->id,
            ],
            [
                'email' => 'admin.cuni@example.com',
                'name' => 'Admin CUNI User',
                'role_id' => $adminCuni->id,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role_id' => $user['role_id'],
                    'password' => Hash::make('password123'),
                ],
            );
        }
    }
}
