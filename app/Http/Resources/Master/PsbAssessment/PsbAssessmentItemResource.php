<?php

namespace App\Http\Resources\Master\PsbAssessment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PsbAssessmentItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->criteria_code,
            'name' => $this->name,
            'criteria_type' => $this->criteriaType?->name,
            'score' => $this->score,
            'status' => $this->is_active ? 'Aktif' : 'Non Aktif',
        ];
    }
}
