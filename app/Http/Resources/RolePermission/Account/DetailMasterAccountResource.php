<?php

namespace App\Http\Resources\RolePermission\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailMasterAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'role' => $this->role?->name,
            'is_active' => $this->is_active ? 'Aktif' : 'Non Aktif',
        ];
    }
}
