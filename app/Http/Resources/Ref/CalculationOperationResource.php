<?php

namespace App\Http\Resources\Ref;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalculationOperationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'name' => $this->name,
        ];
    }
}
