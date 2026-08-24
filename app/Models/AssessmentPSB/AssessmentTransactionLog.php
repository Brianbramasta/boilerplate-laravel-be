<?php

namespace App\Models\AssessmentPSB;

use App\Models\Ref\AssessmentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentTransactionLog extends Model
{
    use HasUuids;

    protected $table = 'log_assessment_transactionals';

    protected $fillable = [
        'assessment_transaction_id',
        'from_status_id',
        'to_status_id',
        'changed_by',
        'changed_at',
        'remark',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(AssessmentTransaction::class, 'assessment_transaction_id');
    }

    public function fromStatus()
    {
        return $this->belongsTo(AssessmentStatus::class, 'from_status_id');
    }

    public function toStatus()
    {
        return $this->belongsTo(AssessmentStatus::class, 'to_status_id');
    }
}
