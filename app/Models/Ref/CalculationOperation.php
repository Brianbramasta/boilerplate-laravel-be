<?php

namespace App\Models\Ref;

use App\Models\Concerns\LogsMasterDataChanges;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalculationOperation extends Model
{
    use HasUuids, LogsMasterDataChanges, SoftDeletes;

    protected $table = 'ref_calculation_operations';

    protected $fillable = [
        'code',
        'symbol',
        'name',
        'is_active',
    ];
}
