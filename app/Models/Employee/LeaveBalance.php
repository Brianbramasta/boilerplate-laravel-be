<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $table = 'tr_leave_balances';

    protected $fillable = ['user_id', 'leave_type_id', 'year', 'total_quota', 'used_quota', 'remaining_balance'];

    protected $casts = [
        'total_quota' => 'integer',
        'used_quota' => 'integer',
        'remaining_balance' => 'integer',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}