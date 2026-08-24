<?php

namespace App\Models\Master;

use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentCategory extends Model
{
    use HasUuids, LogsMasterDataChanges;

    protected $table = 'mt_assessment_categories';

    protected $fillable = [
        'name',
        'code',
        'max_score',
        'is_active',
    ];

    public function criteria()
    {
        return $this->hasMany(AssessmentCriteria::class, 'assessment_category_id');
    }
}
