<?php

namespace App\Models\AssessmentPSB;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentStepValue extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'dt_assessment_step_values';

    protected $fillable = [
        'assessment_information_id',
        'step_id',
        'step_code',
        'step_name',
        'order_number',
    ];

    public function information()
    {
        return $this->belongsTo(AssessmentInformation::class, 'assessment_information_id');
    }

    public function dataValues()
    {
        return $this->hasMany(AssessmentStepDataValue::class, 'assessment_step_values_id');
    }
}
