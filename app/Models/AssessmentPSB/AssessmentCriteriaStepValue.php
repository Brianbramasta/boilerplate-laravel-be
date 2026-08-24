<?php

namespace App\Models\AssessmentPSB;

use App\Models\Master\AssessmentCriteriaStep;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentCriteriaStepValue extends Model
{
    use HasUuids;

    protected $table = 'tr_assessment_criteria_step_values';

    protected $fillable = [
        'criteria_step_id',
        'variable_name',
        'variable_code',
        'order',
        'type_data',
        'is_active',
    ];

    public function criteriaStep()
    {
        return $this->belongsTo(AssessmentCriteriaStep::class, 'criteria_step_id');
    }
}
