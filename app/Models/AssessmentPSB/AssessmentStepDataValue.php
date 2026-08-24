<?php

namespace App\Models\AssessmentPSB;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentStepDataValue extends Model
{
    use HasUuids;

    protected $table = 'dt_assessment_step_data_values';

    protected $fillable = [
        'assessment_step_values_id',
        'data_code',
        'data_name',
        'type_data',
        'value',
    ];

    public function stepValue()
    {
        return $this->belongsTo(AssessmentStepValue::class, 'assessment_step_values_id');
    }
}
