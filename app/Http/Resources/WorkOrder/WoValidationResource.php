<?php

namespace App\Http\Resources\WorkOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class WoValidationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'status' => $this->status ?? 'DRAFT',
            'no_wo' => $this->no_wo,
            'customer_name' => $this->customer_name,
            'branch_name' => $this->branch_name,
            'pppoe' => $this->pppoe,
            'katim' => $this->katim,
            'team' => $this->team,
            'access_erp' => $this->access_erp,
            'start' => $this->start,
            'end' => $this->end,
            'duration' => $this->duration,
            'distance_sales' => $this->distance_sales,
            'distance_team' => $this->distance_team,
            'dropcore' => $this->dropcore,
            'notes' => $this->notes ?? '',
            'assessments' => $this->whenLoaded('assessments', function () {
                return $this->assessments->map(function ($assessment) {
                    return [
                        'assessment' => [
                            'id' => $assessment->id,
                            'name' => $assessment->name,
                            'assessment_code_id' => $assessment->assessment_code_id,
                            'created_by' => $assessment->created_by,
                            'is_active' => $assessment->is_active,
                            'created_at' => $assessment->created_at,
                            'updated_at' => $assessment->updated_at,
                            'deleted_at' => $assessment->deleted_at,
                            'id_validate' => $assessment->id_validate,
                        ],
                        'categories' => $this->whenLoaded('categories', function () use ($assessment) {
                            return $assessment->categories->map(function ($category) {
                                return [
                                    'category' => [
                                        'id' => $category->id,
                                        'name' => $category->name,
                                        'code' => $category->code,
                                        'max_score' => $category->max_score,
                                        'is_active' => $category->is_active,
                                        'created_at' => $category->created_at,
                                        'updated_at' => $category->updated_at,
                                        'deleted_at' => $category->deleted_at,
                                    ],
                                    'total_weight_active' => $category->total_weight_active ?? 100,
                                    'items' => $this->whenLoaded('items', function () use ($category) {
                                        return $category->items->map(function ($item) {
                                            return [
                                                'id' => $item->id,
                                                'assessment_id' => $item->assessment_id,
                                                'assessment_category_id' => $item->assessment_category_id,
                                                'name' => $item->name,
                                                'criteria_code' => $item->criteria_code,
                                                'criteria_type_id' => $item->criteria_type_id,
                                                'operation_id' => $item->operation_id,
                                                'score_type_id' => $item->score_type_id,
                                                'score' => $item->score,
                                                'is_active' => $item->is_active,
                                                'created_by' => $item->created_by,
                                                'created_at' => $item->created_at,
                                                'updated_at' => $item->updated_at,
                                                'deleted_at' => $item->deleted_at,
                                                'operation_name' => $item->operation_name,
                                                'operation_code' => $item->operation_code,
                                                'operation_symbol' => $item->operation_symbol,
                                                'score_name' => $item->score_name,
                                                'score_code' => $item->score_code,
                                                'score_symbol' => $item->score_symbol,
                                                'criteria_type_name' => $item->criteria_type_name,
                                                'criteria_type_code' => $item->criteria_type_code,
                                                'obtained_score' => $item->obtained_score ?? 0,
                                                'calculated_value' => $item->calculated_value,
                                                'description' => $item->description,
                                                'category' => $this->whenLoaded('category', function () use ($item) {
                                                    return [
                                                        'id' => $item->category->id,
                                                        'name' => $item->category->name,
                                                        'code' => $item->category->code,
                                                        'max_score' => $item->category->max_score,
                                                        'is_active' => $item->category->is_active,
                                                        'created_at' => $item->category->created_at,
                                                        'updated_at' => $item->category->updated_at,
                                                        'deleted_at' => $item->category->deleted_at,
                                                    ];
                                                }),
                                                'operation' => $this->whenLoaded('operation', function () use ($item) {
                                                    return $item->operation ? [
                                                        'id' => $item->operation->id,
                                                        'code' => $item->operation->code,
                                                        'symbol' => $item->operation->symbol,
                                                        'name' => $item->operation->name,
                                                        'is_active' => $item->operation->is_active,
                                                        'created_at' => $item->operation->created_at,
                                                        'updated_at' => $item->operation->updated_at,
                                                        'deleted_at' => $item->operation->deleted_at,
                                                    ] : null;
                                                }),
                                                'score_type' => $this->whenLoaded('scoreType', function () use ($item) {
                                                    return [
                                                        'id' => $item->scoreType->id,
                                                        'code' => $item->scoreType->code,
                                                        'name' => $item->scoreType->name,
                                                        'symbol' => $item->scoreType->symbol,
                                                        'is_active' => $item->scoreType->is_active,
                                                        'created_at' => $item->scoreType->created_at,
                                                        'updated_at' => $item->scoreType->updated_at,
                                                        'deleted_at' => $item->scoreType->deleted_at,
                                                    ];
                                                }),
                                                'criteria_type' => $this->whenLoaded('criteriaType', function () use ($item) {
                                                    return [
                                                        'id' => $item->criteriaType->id,
                                                        'code' => $item->criteriaType->code,
                                                        'name' => $item->criteriaType->name,
                                                        'is_active' => $item->criteriaType->is_active,
                                                        'created_at' => $item->criteriaType->created_at,
                                                        'updated_at' => $item->criteriaType->updated_at,
                                                        'deleted_at' => $item->criteriaType->deleted_at,
                                                    ];
                                                }),
                                                'rules' => $this->whenLoaded('rules', function () use ($item) {
                                                    return $item->rules->map(function ($rule) {
                                                        return [
                                                            'id' => $rule->id,
                                                            'assessment_criteria_id' => $rule->assessment_criteria_id,
                                                            'rule_code' => $rule->rule_code,
                                                            'rule' => $rule->rule,
                                                            'is_active' => $rule->is_active,
                                                            'created_by' => $rule->created_by,
                                                            'created_at' => $rule->created_at,
                                                            'updated_at' => $rule->updated_at,
                                                            'deleted_at' => $rule->deleted_at,
                                                        ];
                                                    });
                                                }),
                                                'steps' => $this->whenLoaded('steps', function () use ($item) {
                                                    return $item->steps->map(function ($step) {
                                                        return [
                                                            'id' => $step->id,
                                                            'assessment_criteria_id' => $step->assessment_criteria_id,
                                                            'sequence' => $step->sequence,
                                                            'step_id' => $step->step_id,
                                                            'step_code' => $step->step_code,
                                                            'step_name' => $step->step_name,
                                                            'created_at' => $step->created_at,
                                                            'updated_at' => $step->updated_at,
                                                            'deleted_at' => $step->deleted_at,
                                                            'step_default_values' => $this->whenLoaded('stepDefaultValues', function () use ($step) {
                                                                return $step->stepDefaultValues->map(function ($defaultValue) {
                                                                    return [
                                                                        'key' => $defaultValue->key,
                                                                        'value' => $defaultValue->value,
                                                                        'is_active' => $defaultValue->is_active,
                                                                        'type_data' => $defaultValue->type_data,
                                                                        'timestamp' => $defaultValue->timestamp,
                                                                    ];
                                                                });
                                                            }),
                                                            'step_values' => $this->whenLoaded('stepValues', function () use ($step) {
                                                                return $step->stepValues->map(function ($stepValue) {
                                                                    return [
                                                                        'id' => $stepValue->id,
                                                                        'criteria_step_id' => $stepValue->criteria_step_id,
                                                                        'variable_name' => $stepValue->variable_name,
                                                                        'variable_code' => $stepValue->variable_code,
                                                                        'order' => $stepValue->order,
                                                                        'type_data' => $stepValue->type_data,
                                                                        'is_active' => $stepValue->is_active,
                                                                        'created_at' => $stepValue->created_at,
                                                                        'updated_at' => $stepValue->updated_at,
                                                                        'deleted_at' => $stepValue->deleted_at,
                                                                        'value' => $stepValue->value,
                                                                        'timestamp' => $stepValue->timestamp,
                                                                        'is_default' => $stepValue->is_default,
                                                                    ];
                                                                });
                                                            }),
                                                        ];
                                                    });
                                                }),
                                            ];
                                        });
                                    }),
                                ];
                            });
                        }),
                    ];
                });
            }),
            'details' => $this->whenLoaded('details', function () {
                return $this->details->map(function ($detail) {
                    return [
                        'assessment' => [
                            'id' => $detail->assessment->id,
                            'name' => $detail->assessment->name,
                            'assessment_code_id' => $detail->assessment->assessment_code_id,
                            'created_by' => $detail->assessment->created_by,
                            'is_active' => $detail->assessment->is_active,
                            'created_at' => $detail->assessment->created_at,
                            'updated_at' => $detail->assessment->updated_at,
                            'deleted_at' => $detail->assessment->deleted_at,
                            'id_validate' => $detail->assessment->id_validate,
                        ],
                        'detail_id' => $detail->detail_id,
                        'total_score' => $detail->total_score ?? 0,
                        'categories' => $this->whenLoaded('categories', function () use ($detail) {
                            return $detail->categories->map(function ($category) {
                                return [
                                    'category' => [
                                        'id' => $category->id,
                                        'name' => $category->name,
                                        'code' => $category->code,
                                        'max_score' => $category->max_score,
                                        'is_active' => $category->is_active,
                                        'created_at' => $category->created_at,
                                        'updated_at' => $category->updated_at,
                                        'deleted_at' => $category->deleted_at,
                                    ],
                                    'total_weight_active' => $category->total_weight_active ?? 100,
                                    'items' => $this->whenLoaded('items', function () use ($category) {
                                        return $category->items->map(function ($item) {
                                            return [
                                                'id' => $item->id,
                                                'assessment_id' => $item->assessment_id,
                                                'assessment_category_id' => $item->assessment_category_id,
                                                'name' => $item->name,
                                                'criteria_code' => $item->criteria_code,
                                                'criteria_type_id' => $item->criteria_type_id,
                                                'operation_id' => $item->operation_id,
                                                'score_type_id' => $item->score_type_id,
                                                'score' => $item->score,
                                                'is_active' => $item->is_active,
                                                'created_by' => $item->created_by,
                                                'created_at' => $item->created_at,
                                                'updated_at' => $item->updated_at,
                                                'deleted_at' => $item->deleted_at,
                                                'operation_name' => $item->operation_name,
                                                'operation_code' => $item->operation_code,
                                                'operation_symbol' => $item->operation_symbol,
                                                'score_name' => $item->score_name,
                                                'score_code' => $item->score_code,
                                                'score_symbol' => $item->score_symbol,
                                                'criteria_type_name' => $item->criteria_type_name,
                                                'criteria_type_code' => $item->criteria_type_code,
                                                'obtained_score' => $item->obtained_score ?? 0,
                                                'calculated_value' => $item->calculated_value,
                                                'description' => $item->description,
                                                'category' => $this->whenLoaded('category', function () use ($item) {
                                                    return [
                                                        'id' => $item->category->id,
                                                        'name' => $item->category->name,
                                                        'code' => $item->category->code,
                                                        'max_score' => $item->category->max_score,
                                                        'is_active' => $item->category->is_active,
                                                        'created_at' => $item->category->created_at,
                                                        'updated_at' => $item->category->updated_at,
                                                        'deleted_at' => $item->category->deleted_at,
                                                    ];
                                                }),
                                                'operation' => $this->whenLoaded('operation', function () use ($item) {
                                                    return $item->operation ? [
                                                        'id' => $item->operation->id,
                                                        'code' => $item->operation->code,
                                                        'symbol' => $item->operation->symbol,
                                                        'name' => $item->operation->name,
                                                        'is_active' => $item->operation->is_active,
                                                        'created_at' => $item->operation->created_at,
                                                        'updated_at' => $item->operation->updated_at,
                                                        'deleted_at' => $item->operation->deleted_at,
                                                    ] : null;
                                                }),
                                                'score_type' => $this->whenLoaded('scoreType', function () use ($item) {
                                                    return [
                                                        'id' => $item->scoreType->id,
                                                        'code' => $item->scoreType->code,
                                                        'name' => $item->scoreType->name,
                                                        'symbol' => $item->scoreType->symbol,
                                                        'is_active' => $item->scoreType->is_active,
                                                        'created_at' => $item->scoreType->created_at,
                                                        'updated_at' => $item->scoreType->updated_at,
                                                        'deleted_at' => $item->scoreType->deleted_at,
                                                    ];
                                                }),
                                                'criteria_type' => $this->whenLoaded('criteriaType', function () use ($item) {
                                                    return [
                                                        'id' => $item->criteriaType->id,
                                                        'code' => $item->criteriaType->code,
                                                        'name' => $item->criteriaType->name,
                                                        'is_active' => $item->criteriaType->is_active,
                                                        'created_at' => $item->criteriaType->created_at,
                                                        'updated_at' => $item->criteriaType->updated_at,
                                                        'deleted_at' => $item->criteriaType->deleted_at,
                                                    ];
                                                }),
                                                'rules' => $this->whenLoaded('rules', function () use ($item) {
                                                    return $item->rules->map(function ($rule) {
                                                        return [
                                                            'id' => $rule->id,
                                                            'assessment_criteria_id' => $rule->assessment_criteria_id,
                                                            'rule_code' => $rule->rule_code,
                                                            'rule' => $rule->rule,
                                                            'is_active' => $rule->is_active,
                                                            'created_by' => $rule->created_by,
                                                            'created_at' => $rule->created_at,
                                                            'updated_at' => $rule->updated_at,
                                                            'deleted_at' => $rule->deleted_at,
                                                        ];
                                                    });
                                                }),
                                                'steps' => $this->whenLoaded('steps', function () use ($item) {
                                                    return $item->steps->map(function ($step) {
                                                        return [
                                                            'id' => $step->id,
                                                            'assessment_criteria_id' => $step->assessment_criteria_id,
                                                            'sequence' => $step->sequence,
                                                            'step_id' => $step->step_id,
                                                            'step_code' => $step->step_code,
                                                            'step_name' => $step->step_name,
                                                            'created_at' => $step->created_at,
                                                            'updated_at' => $step->updated_at,
                                                            'deleted_at' => $step->deleted_at,
                                                            'step_default_values' => $this->whenLoaded('stepDefaultValues', function () use ($step) {
                                                                return $step->stepDefaultValues->map(function ($defaultValue) {
                                                                    return [
                                                                        'key' => $defaultValue->key,
                                                                        'value' => $defaultValue->value,
                                                                        'is_active' => $defaultValue->is_active,
                                                                        'type_data' => $defaultValue->type_data,
                                                                        'timestamp' => $defaultValue->timestamp,
                                                                    ];
                                                                });
                                                            }),
                                                            'step_values' => $this->whenLoaded('stepValues', function () use ($step) {
                                                                return $step->stepValues->map(function ($stepValue) {
                                                                    return [
                                                                        'id' => $stepValue->id,
                                                                        'criteria_step_id' => $stepValue->criteria_step_id,
                                                                        'variable_name' => $stepValue->variable_name,
                                                                        'variable_code' => $stepValue->variable_code,
                                                                        'order' => $stepValue->order,
                                                                        'type_data' => $stepValue->type_data,
                                                                        'is_active' => $stepValue->is_active,
                                                                        'created_at' => $stepValue->created_at,
                                                                        'updated_at' => $stepValue->updated_at,
                                                                        'deleted_at' => $stepValue->deleted_at,
                                                                        'value' => $stepValue->value,
                                                                        'timestamp' => $stepValue->timestamp,
                                                                        'is_default' => $stepValue->is_default,
                                                                    ];
                                                                });
                                                            }),
                                                        ];
                                                    });
                                                }),
                                            ];
                                        });
                                    }),
                                ];
                            });
                        }),
                    ];
                });
            }),
        ];
    }
}
