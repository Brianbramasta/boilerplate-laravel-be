<?php

namespace App\Models\Master;

use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentCriteriaRule extends Model
{
    use HasUuids, LogsMasterDataChanges, SoftDeletes;

    protected $table = 'mt_assessment_criteria_rules';

    protected $fillable = [
        'assessment_criteria_id',
        'rule_code',
        'rule',
        'is_active',
        'created_by',
    ];

    public function criteria()
    {
        return $this->belongsTo(AssessmentCriteria::class, 'assessment_criteria_id');
    }
}
