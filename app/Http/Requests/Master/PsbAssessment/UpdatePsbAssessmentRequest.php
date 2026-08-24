<?php

namespace App\Http\Requests\Master\PsbAssessment;

use App\Http\Requests\BaseRequest;
use App\Models\Ref\CriteriaType;

class UpdatePsbAssessmentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'assessment_id' => ['required', 'uuid', 'exists:mt_assessments,id'],
            'assessment_category_id' => ['required', 'uuid', 'exists:mt_assessment_categories,id'],
            'criteria_type_id' => ['required', 'uuid', 'exists:ref_criteria_types,id'],
            'is_active' => ['required', 'boolean'],
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

            // rules: kirim daftar penuh (lihat StorePsbAssessmentRequest untuk create).
            // Sertakan `id` pada item yang sudah ada supaya diupdate di tempat;
            // tanpa `id` berarti item baru. Item lama yang tidak disebut lagi
            // otomatis soft delete — sama seperti extend_component di psb-variable.
            'rules' => ['sometimes', 'array'],
            'rules.*.id' => ['sometimes', 'nullable', 'uuid'],
            'rules.*.rule' => ['required_with:rules', 'string'],
            'rules.*.is_active' => ['required_with:rules', 'boolean'],
        ];
    }
}
