<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'tr_leave_requests';

    protected $fillable = ['user_id', 'leave_type_id', 'start_date', 'end_date', 'total_days', 'reason', 'status'];

    protected $casts = [
        'total_days' => 'integer',
    ];

    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at'];

    const STATUS_SUBMITTED = 'SUBMITTED';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_CANCELLED = 'CANCELLED';

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class);
    }

    public function isCancellable()
    {
        return $this->status === self::STATUS_SUBMITTED;
    }
}