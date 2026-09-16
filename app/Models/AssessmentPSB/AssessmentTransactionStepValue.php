<?php

namespace App\Models\AssessmentPSB;

use App\Models\Concerns\LogsAssessmentDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentTransactionStepValue extends Model
{
    use HasUuids, SoftDeletes, LogsAssessmentDataChanges;

    protected $table = 'tr_assessment_step_values';

    protected $fillable = [
        'criteria_transaction_id',
        'order',
        'step_code',
        'variable_code',
        'variable_name',
    ];

    public function detailCriteria()
    {
        return $this->belongsTo(AssessmentTransactionDetailCriteria::class, 'criteria_transaction_id');
    }

    public function inputValues()
    {
        return $this->hasMany(StepInputValue::class, 'assessment_step_values_id');
    }
}
