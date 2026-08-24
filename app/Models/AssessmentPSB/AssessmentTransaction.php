<?php

namespace App\Models\AssessmentPSB;

use App\Models\Ref\AssessmentCode;
use App\Models\Ref\AssessmentResult;
use App\Models\Ref\AssessmentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentTransaction extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'tr_assessments';

    protected $fillable = [
        'pppoe_secret',
        'assessment_code_id',
        'work_order_id',
        'current_pic_id',
        'company_code',
        'system_code',
        'started_at',
        'completed_at',
        'status_id',
        'result_id',
        'is_had_draft',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function assessmentCode()
    {
        return $this->belongsTo(AssessmentCode::class, 'assessment_code_id');
    }

    public function status()
    {
        return $this->belongsTo(AssessmentStatus::class, 'status_id');
    }

    public function result()
    {
        return $this->belongsTo(AssessmentResult::class, 'result_id');
    }

    public function currentPic()
    {
        return $this->belongsTo(User::class, 'current_pic_id');
    }

    public function details()
    {
        return $this->hasMany(AssessmentTransactionDetail::class, 'assessment_transaction_id');
    }

    public function information()
    {
        return $this->hasOne(AssessmentInformation::class, 'assessment_transaction_id');
    }

    public function logs()
    {
        return $this->hasMany(AssessmentTransactionLog::class, 'assessment_transaction_id');
    }
}
