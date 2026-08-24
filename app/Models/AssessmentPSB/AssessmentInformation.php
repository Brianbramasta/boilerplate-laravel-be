<?php

namespace App\Models\AssessmentPSB;

use App\Models\Ref\AssessmentCode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentInformation extends Model
{
    use HasUuids;

    protected $table = 'dt_assessment_informations';

    protected $fillable = [
        'assessment_transaction_id',
        'branch_id',
        'branch_name',
        'regional_name',
        'pppoe_secret',
        'wo_id',
        'no_wo',
        'customer_name',
        'assessment_code_id',
        'work_order_date',
        'start_work_order',
        'end_work_order',
        'duration_minute',
        'address',
        'cust_extra',
        'erp_extra',
    ];

    protected function casts(): array
    {
        return [
            'work_order_date' => 'date',
            'start_work_order' => 'datetime',
            'end_work_order' => 'datetime',
            'cust_extra' => 'array',
            'erp_extra' => 'array',
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(AssessmentTransaction::class, 'assessment_transaction_id');
    }

    public function assessmentCode()
    {
        return $this->belongsTo(AssessmentCode::class, 'assessment_code_id');
    }

    public function technicalInformation()
    {
        return $this->hasOne(AssessmentTechnicalInformation::class, 'assessment_information_id');
    }

    public function picInformations()
    {
        return $this->hasMany(AssessmentPicInformation::class, 'assessment_information_id');
    }

    public function woPsbInformation()
    {
        return $this->hasOne(AssessmentWoPsbInformation::class, 'assessment_information_id');
    }

    public function stepValues()
    {
        return $this->hasMany(AssessmentStepValue::class, 'assessment_information_id');
    }
}
