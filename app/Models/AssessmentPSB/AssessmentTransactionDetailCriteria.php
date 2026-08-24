<?php

namespace App\Models\AssessmentPSB;

use App\Models\Master\AssessmentCategory;
use App\Models\Master\AssessmentCriteria;
use App\Models\Ref\CalculationOperation;
use App\Models\Ref\CriteriaType;
use App\Models\Ref\ScoreType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentTransactionDetailCriteria extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'tr_assessment_detail_criteria';

    protected $fillable = [
        'assessment_detail_id',
        'criteria_id',
        'criteria_name',
        'category_id',
        'category_name',
        'criteria_max_score',
        'criteria_type_id',
        'criteria_type_name',
        'operation_id',
        'score_type_id',
        'max_score',
        'obtained_score',
        'calculated_value',
        'description',
    ];

    public function detail()
    {
        return $this->belongsTo(AssessmentTransactionDetail::class, 'assessment_detail_id');
    }

    public function criteria()
    {
        return $this->belongsTo(AssessmentCriteria::class, 'criteria_id');
    }

    public function category()
    {
        return $this->belongsTo(AssessmentCategory::class, 'category_id');
    }

    public function criteriaType()
    {
        return $this->belongsTo(CriteriaType::class, 'criteria_type_id');
    }

    public function operation()
    {
        return $this->belongsTo(CalculationOperation::class, 'operation_id');
    }

    public function scoreType()
    {
        return $this->belongsTo(ScoreType::class, 'score_type_id');
    }

    public function detailRules()
    {
        return $this->hasMany(AssessmentTransactionDetailRule::class, 'criteria_transaction_id');
    }

    public function stepValues()
    {
        return $this->hasMany(AssessmentTransactionStepValue::class, 'criteria_transaction_id');
    }
}
