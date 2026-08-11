<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasUuids;

    protected $table = 'mt_roles';

    protected $fillable = [
        'name',
        'slug',
        'is_system',
        'is_active',
    ];

    public function roleMenus()
    {
        return $this->hasMany(RoleMenu::class);
    }
}
