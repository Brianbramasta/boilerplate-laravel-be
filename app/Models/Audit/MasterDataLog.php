<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MasterDataLog extends Model
{
    use HasUuids;

    protected $table = 'log_master_data_changes';

    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'action',
        'old_values',
        'new_values',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function loggable()
    {
        return $this->morphTo();
    }
}
