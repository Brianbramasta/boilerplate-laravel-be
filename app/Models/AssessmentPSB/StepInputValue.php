<?php

namespace App\Models\AssessmentPSB;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class StepInputValue extends Model
{
    use HasUuids;

    protected $table = 'dt_step_input_values';

    protected $fillable = [
        'assessment_step_values_id',
        'variable_name',
        'variable_code',
        'order',
        'type_data',
        'value',
    ];

    public function stepValue()
    {
        return $this->belongsTo(AssessmentTransactionStepValue::class, 'assessment_step_values_id');
    }
}
