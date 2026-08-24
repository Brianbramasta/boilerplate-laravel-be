<?php

namespace App\Http\Resources\Master\PsbVariable;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPsbWoStepResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'order' => $this->order,
            'name' => $this->name,
            'existing_data' => $this->existingData(),
            'status' => $this->is_active ? 'Aktif' : 'Non Aktif',
            'description' => $this->description,
            'default_component' => $this->default_component,
            'extend_component' => $this->extend_component,
        ];
    }

    protected function existingData(): array
    {
        $existing = [];

        if ($this->is_data) {
            $existing[] = 'data';
        }

        if ($this->is_picture) {
            $existing[] = 'foto';
        }

        return $existing;
    }
}
