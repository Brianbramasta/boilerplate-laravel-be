<?php

namespace App\Models\AssessmentPSB;

use App\Models\Master\Assessment;
use App\Models\Ref\AssessmentCode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentTransactionDetail extends Model
{
    use HasUuids;

    protected $table = 'tr_assessment_details';

    protected $fillable = [
        'assessment_transaction_id',
        'assessment_id',
        'assessment_name',
        'assessment_code_id',
        'total_score',
    ];

    public function transaction()
    {
        return $this->belongsTo(AssessmentTransaction::class, 'assessment_transaction_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function assessmentCode()
    {
        return $this->belongsTo(AssessmentCode::class, 'assessment_code_id');
    }

    public function detailCriteria()
    {
        return $this->hasMany(AssessmentTransactionDetailCriteria::class, 'assessment_detail_id');
    }
}
