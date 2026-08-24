<?php

namespace App\Models\AssessmentPSB;

use App\Models\Master\AssessmentCriteriaRule;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentTransactionDetailRule extends Model
{
    use HasUuids;

    protected $table = 'tr_assessment_detail_rules';

    protected $fillable = [
        'criteria_transaction_id',
        'rule_id',
        'rule_name',
        'expected_value',
        'actual_value',
        'is_pass',
    ];

    public function detailCriteria()
    {
        return $this->belongsTo(AssessmentTransactionDetailCriteria::class, 'criteria_transaction_id');
    }

    public function rule()
    {
        return $this->belongsTo(AssessmentCriteriaRule::class, 'rule_id');
    }
}
