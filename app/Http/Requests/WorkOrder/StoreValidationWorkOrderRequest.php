<?php

namespace App\Http\Requests\WorkOrder;

use App\Http\Requests\BaseRequest;

class StoreValidationWorkOrderRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'status' => ['required', 'string'], // UUID status
            'notes' => ['nullable', 'string'],
            'assessments' => ['required', 'array', 'min:1'],
            'assessments.*.assessment' => ['required', 'array'],
            'assessments.*.assessment.id' => ['required', 'string'],
            'assessments.*.assessment.name' => ['required', 'string'],
            'assessments.*.categories' => ['required', 'array', 'min:1'],
            'assessments.*.categories.*.category' => ['required', 'array'],
            'assessments.*.categories.*.category.id' => ['required', 'string'],
            'assessments.*.categories.*.category.name' => ['required', 'string'],
            'assessments.*.categories.*.total_weight_active' => ['required', 'integer'],
            'assessments.*.categories.*.items' => ['required', 'array', 'min:1'],
            'assessments.*.categories.*.items.*.id' => ['required', 'string'],
            'assessments.*.categories.*.items.*.code' => ['required', 'string'],
            'assessments.*.categories.*.items.*.name' => ['required', 'string'],
            'assessments.*.categories.*.items.*.criteria_type' => ['required', 'string'],
            'assessments.*.categories.*.items.*.score' => ['required', 'numeric'],
            'assessments.*.categories.*.items.*.status' => ['required', 'string'],
            'assessments.*.categories.*.items.*.value' => ['required', 'array'],

            // value.criterias & value.rules & value.notes
            'assessments.*.categories.*.items.*.value.criterias' => ['required', 'array'],
            'assessments.*.categories.*.items.*.value.criterias.*.variable_key' => ['required', 'string'],
            'assessments.*.categories.*.items.*.value.criterias.*.value' => ['required'],

            'assessments.*.categories.*.items.*.value.rules' => ['required', 'array'],
            'assessments.*.categories.*.items.*.value.rules.*.id' => ['required', 'string'],
            'assessments.*.categories.*.items.*.value.rules.*.rule' => ['required', 'string'],
            'assessments.*.categories.*.items.*.value.rules.*.is_checked' => ['required', 'boolean'],

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
