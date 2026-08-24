<?php

namespace App\Models\Master;

use App\Models\AssessmentPSB\AssessmentCriteriaStepValue;
use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentCriteriaStep extends Model
{
    use HasUuids, LogsMasterDataChanges;

    protected $table = 'mt_assessment_criteria_steps';

    protected $fillable = [
        'assessment_criteria_id',
        'sequence',
        'step_id',
        'step_code',
        'step_name',
    ];

    public function criteria()
    {
        return $this->belongsTo(AssessmentCriteria::class, 'assessment_criteria_id');
    }

    public function stepValues()
    {
        return $this->hasMany(AssessmentCriteriaStepValue::class, 'criteria_step_id');
    }
}
