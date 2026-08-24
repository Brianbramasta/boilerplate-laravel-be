<?php

namespace App\Models\RolePermission;

use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasUuids, LogsMasterDataChanges, SoftDeletes;

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
