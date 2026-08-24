<?php

namespace App\Models\Ref;

use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentStatus extends Model
{
    use HasUuids, LogsMasterDataChanges, SoftDeletes;

    protected $table = 'ref_assessment_statuses';

    protected $fillable = [
        'name',
        'code',
        'is_final',
        'is_active',
    ];
}
