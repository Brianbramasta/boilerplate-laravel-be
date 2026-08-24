<?php

namespace App\Models\Ref;

use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CriteriaType extends Model
{
    use HasUuids, LogsMasterDataChanges;

    protected $table = 'ref_criteria_types';

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];
}
