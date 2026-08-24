<?php

namespace App\Http\Resources\Master\PsbAssessment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PsbAssessmentDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->criteria_code,
            'name' => $this->name,
            'assessment' => [
                'id' => $this->assessment?->id,
                'name' => $this->assessment?->name,
            ],
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'criteria_type' => [
                'id' => $this->criteriaType?->id,
                'code' => $this->criteriaType?->code,
                'name' => $this->criteriaType?->name,
            ],
            'operation' => $this->operation ? [
                'id' => $this->operation->id,
                'code' => $this->operation->code,
                'symbol' => $this->operation->symbol,
                'name' => $this->operation->name,
            ] : null,
            'score' => $this->score,
            'status' => $this->is_active ? 'Aktif' : 'Non Aktif',
            'mapping' => $this->steps->map(fn ($step) => [
                'sequence' => $step->sequence,
                'step_id' => $step->step_id,
                'step_code' => $step->step_code,
                'step_name' => $step->step_name,
                'variable_key' => $step->stepValues->first()?->variable_code,
                'type_data' => $step->stepValues->first()?->type_data,
            ]),
            'rules' => $this->rules->map(fn ($rule) => [
                'id' => $rule->id,
                'rule_code' => $rule->rule_code,
                'rule' => $rule->rule,
                'is_active' => $rule->is_active,
            ]),
        ];
    }
}
