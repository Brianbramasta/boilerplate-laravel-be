<?php

namespace App\Models\Ref;

use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
    use HasUuids, LogsMasterDataChanges;

    protected $table = 'ref_assessment_results';

    protected $fillable = [
        'code',
        'name',
        'severity',
        'description',
        'display_order',
        'is_active',
    ];
}
