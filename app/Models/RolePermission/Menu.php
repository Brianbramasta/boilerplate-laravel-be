<?php

namespace App\Models\RolePermission;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'code'];

    protected $hidden = ['created_at', 'updated_at'];
}