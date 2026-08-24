<?php

namespace App\Models\Master;

use App\Models\Concerns\LogsMasterDataChanges;
use App\Models\Ref\AssessmentCode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasUuids, LogsMasterDataChanges;

    protected $table = 'mt_assessments';

    protected $fillable = [
        'name',
        'assessment_code_id',
        'created_by',
        'is_active',
    ];

    public function assessmentCode()
    {
        return $this->belongsTo(AssessmentCode::class, 'assessment_code_id');
    }

    public function criteria()
    {
        return $this->hasMany(AssessmentCriteria::class, 'assessment_id');
    }
}
