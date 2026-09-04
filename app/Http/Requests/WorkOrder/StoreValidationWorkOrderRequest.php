<?php

namespace App\Http\Requests\WorkOrder;

use App\Http\Requests\BaseRequest;

class StoreValidationWorkOrderRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'status' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'assessments' => ['required', 'array', 'min:1'],
            'assessments.*.assessment' => ['required', 'array'],
            'assessments.*.assessment.id' => ['required', 'string'],
            'assessments.*.assessment.code' => ['required', 'string'],
            'assessments.*.assessment.name' => ['required', 'string'],
            'assessments.*.assessment.total_score' => ['required', 'numeric'],
            'assessments.*.categories' => ['required', 'array', 'min:1'],

            'assessments.*.categories.*.category' => ['required', 'array'],
            'assessments.*.categories.*.category.criteria_id' => ['required', 'string'],
            'assessments.*.categories.*.category.criteria_name' => ['required', 'string'],
            'assessments.*.categories.*.category.category_id' => ['required', 'string'],
            'assessments.*.categories.*.category.category_name' => ['required', 'string'],
            'assessments.*.categories.*.category.criteria_type_id' => ['required', 'string'],
            'assessments.*.categories.*.category.criteria_type_name' => ['required', 'string'],
            'assessments.*.categories.*.category.operation_id' => ['nullable', 'string'],
            'assessments.*.categories.*.category.score_type_id' => ['required', 'string'],
            'assessments.*.categories.*.category.max_score' => ['required', 'numeric'],

            'assessments.*.categories.*.category.obtained_score' => ['required', 'numeric'],
            'assessments.*.categories.*.category.calculate_value' => ['nullable', 'numeric'],
            'assessments.*.categories.*.category.description' => ['nullable', 'string'],

            'assessments.*.categories.*.items.*.value' => ['required', 'array'],
            'assessments.*.categories.*.items.*.value.criterias' => ['required', 'array'],
            'assessments.*.categories.*.items.*.value.criterias.*.step_code' => ['required', 'string'],
            'assessments.*.categories.*.items.*.value.criterias.*.variable_key' => ['required', 'string'],
            'assessments.*.categories.*.items.*.value.criterias.*.type_data' => ['nullable'],
            'assessments.*.categories.*.items.*.value.criterias.*.value' => ['nullable'],

            'assessments.*.categories.*.items.*.value.rules' => ['required', 'array'],
            'assessments.*.categories.*.items.*.value.rules.*.id' => ['required', 'string'],
            'assessments.*.categories.*.items.*.value.rules.*.rule' => ['required', 'string'],
            'assessments.*.categories.*.items.*.value.rules.*.is_checked' => ['nullable', 'boolean'],
            'assessments.*.categories.*.items.*.value.notes' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            // You can customize error messages here as needed
        ];
    }
}
