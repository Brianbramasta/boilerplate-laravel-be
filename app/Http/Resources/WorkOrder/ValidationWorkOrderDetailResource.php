<?php

namespace App\Http\Resources\WorkOrder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValidationWorkOrderDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Match pattern to sample JSON from @Untitled-3
        return [
            'id' => $this->id,
            'code' => $this->criteria_code ?? $this->code ?? null,
            'name' => $this->criteria_name ?? $this->name ?? null,
            'criteria_type' => $this->criteria_type_name ?? $this->criteria_type ?? 'Single Source',
            'score' => $this->obtained_score ?? $this->score ?? 0,
            'status' => ($this->is_active ?? true) ? 'Aktif' : 'Non Aktif',

            // This 'value' block reflects your target structure
            'value' => [
                'criterias' => collect($this->stepValues ?? [])->flatMap(function ($step) {
                    return collect($step->inputValues ?? [])->map(function ($input) {
                        return [
                            'variable_key' => $input->variable_code,
                            'value' => $input->value,
                        ];
                    });
                })->when(
                    empty($this->stepValues),
                    function ($coll) {
                        // fallback if no stepValues, use current criteria
                        return collect([[
                            'variable_key' => $this->criteria_id ?? $this->id,
                            'value' => $this->obtained_score ?? $this->score ?? 0,
                        ]]);
                    }
                )->values()->all(),

                'rules' => collect($this->detailRules ?? $this->rules ?? [])->map(function ($rule) {
                    return [
                        'id' => $rule->rule_id ?? $rule->id,
                        'rule' => $rule->rule_name ?? $rule->rule,
                        'is_pass' => isset($rule->is_pass) ? ($rule->is_pass ? 1 : 0) : null,
                    ];
                })->values()->all(),
                'notes' => $this->description ?? $this->notes ?? null,
            ],
        ];
    }
}
