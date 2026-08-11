<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'IT', 'slug' => 'it', 'is_system' => true],
            ['name' => 'Superadmin', 'slug' => 'superadmin', 'is_system' => false],
            ['name' => 'Admin CUNI', 'slug' => 'admin-cuni', 'is_system' => false],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role,
            );
        }
    }
}
