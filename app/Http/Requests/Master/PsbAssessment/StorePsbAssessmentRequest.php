<?php

namespace App\Http\Requests\Master\PsbAssessment;

use App\Http\Requests\BaseRequest;
use App\Models\Ref\CriteriaType;

class StorePsbAssessmentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'assessment_id' => ['required', 'uuid', 'exists:mt_assessments,id'],
            'assessment_category_id' => ['required', 'uuid', 'exists:mt_assessment_categories,id'],
            'criteria_type_id' => ['required', 'uuid', 'exists:ref_criteria_types,id'],
            'operation_id' => [
                'nullable',
                'uuid',
                'exists:ref_calculation_operations,id',
                function ($attribute, $value, $fail) {
                    $criteriaType = CriteriaType::find($this->input('criteria_type_id'));

                    if ($criteriaType && $criteriaType->code === 'CALCULATE' && !$value) {
                        $fail('Operasi wajib diisi untuk cara penilaian Kalkulasi.');
                    }
                },
            ],
            'mapping' => ['required', 'array', 'min:1', 'max:2'],
            'mapping.*.step_id' => ['required'],
            'mapping.*.variable_key' => ['required', 'string'],
            'rules' => ['sometimes', 'array'],
            'rules.*.rule' => ['required_with:rules', 'string'],
            'rules.*.is_active' => ['required_with:rules', 'boolean'],
        ];
    }
}
