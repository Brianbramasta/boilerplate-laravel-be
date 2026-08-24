<?php

namespace Database\Seeders;

use App\Models\RolePermission\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::updateOrCreate(
            ['code' => 'master-account'],
            ['label' => 'Master Account', 'is_active' => true],
        );
    }
}
