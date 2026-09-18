<?php

namespace App\Models\AssessmentPSB;

use App\Models\Concerns\LogsAssessmentDataChanges;
use App\Models\Ref\AssessmentCode;
use App\Models\Ref\AssessmentResult;
use App\Models\Ref\AssessmentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentTransaction extends Model
{
    use HasUuids, SoftDeletes, LogsAssessmentDataChanges;

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

    // Relasi untuk mendapatkan average sebagai satu record
    public function scoreAverage()
    {
        return $this->hasOne(AssessmentTransactionDetail::class, 'assessment_transaction_id')
            ->selectRaw('assessment_transaction_id, sum(total_score) as average')
            ->groupBy('assessment_transaction_id');
    }

    // Accessor
    public function getScoreAvgAttribute()
    {
        $average = $this->scoreAverage->average ?? 0;

        $categoryCount = $this->details
            ->flatMap(fn($detail) => $detail->detailCriteria)
            ->map(fn($criteria) => [
                'assessment_detail_id' => $criteria->assessment_detail_id,
                'category_id' => $criteria->category_id,
            ])
            ->unique(
                fn($item) =>
                $item['assessment_detail_id'] . '-' . $item['category_id']
            )
            ->count();

        return $categoryCount > 0
            ? $average / $categoryCount
            : 0;
    }

    public function information()
    {
        return $this->hasOne(AssessmentInformation::class, 'assessment_transaction_id');
    }

    public function logs()
    {
        return $this->hasMany(AssessmentTransactionLog::class, 'assessment_transaction_id');
    }

    public function logsNewest()
    {
        return $this->hasOne(AssessmentTransactionLog::class, 'assessment_transaction_id')
            ->latest();
    }
}
