<?php

namespace App\Models\RolePermission;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    protected $hidden = ['created_at', 'updated_at'];

    public function roleMenus()
    {
        return $this->hasMany(RoleMenu::class);
    }
}