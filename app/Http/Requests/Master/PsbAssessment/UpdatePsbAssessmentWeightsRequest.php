<?php

namespace App\Http\Requests\Master\PsbAssessment;

use App\Http\Requests\BaseRequest;

class UpdatePsbAssessmentWeightsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'assessment_id' => ['required', 'uuid', 'exists:mt_assessments,id'],
            'assessment_category_id' => ['required', 'uuid', 'exists:mt_assessment_categories,id'],
            'weights' => ['required', 'array', 'min:1'],
            'weights.*.id' => ['required', 'uuid', 'exists:mt_assessment_criteria,id'],
            'weights.*.score' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
