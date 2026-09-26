<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    protected $table = 'log_approval_histories';

    protected $fillable = [
        'leave_request_id',
        'actor_id',
        'actor_name',
        'previous_status',
        'next_status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $hidden = ['updated_at'];

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function actor()
    {
        return $this->belongsTo(\App\Models\User::class, 'actor_id');
    }
}