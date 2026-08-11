<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasUuids;

    protected $table = 'mt_menus';

    protected $fillable = [
        'parent_id',
        'code',
        'label',
        'is_active',
    ];

    public function roleMenus()
    {
        return $this->hasMany(RoleMenu::class);
    }
}
