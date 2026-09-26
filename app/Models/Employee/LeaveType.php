<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = 'ref_leave_types';

    protected $fillable = ['code', 'name', 'is_deduct_balance'];

    protected $casts = [
        'is_deduct_balance' => 'boolean',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}