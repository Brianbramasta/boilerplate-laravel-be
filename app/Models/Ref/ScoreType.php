<?php

namespace App\Models\Ref;

use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScoreType extends Model
{
    use HasUuids, LogsMasterDataChanges, SoftDeletes;

    protected $table = 'ref_score_types';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_active',
    ];
}
