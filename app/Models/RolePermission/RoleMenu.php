<?php

namespace App\Models\RolePermission;

use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    use HasUuids, LogsMasterDataChanges;

    protected $table = 'tr_role_menus';

    protected $fillable = [
        'role_id',
        'menu_id',
        'can_view',
        'can_create',
        'can_update',
        'can_delete',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
