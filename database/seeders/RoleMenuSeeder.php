<?php

namespace Database\Seeders;

use App\Models\RolePermission\Menu;
use App\Models\RolePermission\Role;
use App\Models\RolePermission\RoleMenu;
use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::where('code', 'master-account')->firstOrFail();
        $it = Role::where('slug', 'it')->firstOrFail();
        $superadmin = Role::where('slug', 'superadmin')->firstOrFail();

        $fullAccess = ['can_view' => true, 'can_create' => true, 'can_update' => true, 'can_delete' => true];

        RoleMenu::updateOrCreate(
            ['role_id' => $it->id, 'menu_id' => $menu->id],
            $fullAccess,
        );

        RoleMenu::updateOrCreate(
            ['role_id' => $superadmin->id, 'menu_id' => $menu->id],
            $fullAccess,
        );
    }
}
