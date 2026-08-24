<?php

namespace App\Models\Master;

use App\Models\Concerns\LogsMasterDataChanges;
use App\Models\Ref\CalculationOperation;
use App\Models\Ref\CriteriaType;
use App\Models\Ref\ScoreType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentCriteria extends Model
{
    use HasUuids, LogsMasterDataChanges, SoftDeletes;

    protected $table = 'mt_assessment_criteria';

    protected $fillable = [
        'assessment_id',
        'assessment_category_id',
        'name',
        'criteria_code',
        'criteria_type_id',
        'operation_id',
        'score_type_id',
        'score',
        'is_active',
        'created_by',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function category()
    {
        return $this->belongsTo(AssessmentCategory::class, 'assessment_category_id');
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

    public function rules()
    {
        return $this->hasMany(AssessmentCriteriaRule::class, 'assessment_criteria_id');
    }

    public function steps()
    {
        return $this->hasMany(AssessmentCriteriaStep::class, 'assessment_criteria_id');
    }
}
