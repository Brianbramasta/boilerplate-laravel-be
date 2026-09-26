<?php

namespace App\Http\Requests\Employee;

use App\Models\Employee\LeaveBalance;
use App\Models\Employee\LeaveType;
use App\Models\Employee\LeaveRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class CreateLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required|date'],
            'end_date' => ['required|date|after_or_equal:start_date'],
            'reason' => ['required', 'string', 'min:3'],
        ];
    }

    protected function passedValidation()
    {
        $validated = $this->validated();
        $user = auth()->user();
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $leaveType = LeaveType::find($validated['leave_type_id']);

        $year = $startDate->year;
        $balance = LeaveBalance::where('user_id', $user->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('year', $year)
            ->first();

        if (!$balance) {
            throw new ValidationException('Saldo cuti tidak ditemukan.');
        }

        if ($totalDays > $balance->remaining_balance) {
            throw new ValidationException('Saldo cuti tidak mencukupi.');
        }

        $existingRequest = LeaveRequest::where('user_id', $user->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('status', '!=', 'CANCELLED')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($qq) use ($startDate, $endDate) {
                          $qq->where('start_date', '<=', $startDate)
                             ->where('end_date', '>=', $endDate);
                      });
                });
            })
            ->exists();

        if ($existingRequest) {
            throw new ValidationException('Periode cuti bentrok dengan pengajuan Anda yang lain.');
        }

        $validated['total_days'] = $totalDays;
        $validated['status'] = LeaveRequest::STATUS_SUBMITTED;
        $validated['user_id'] = $user->id;

        return $validated;
    }
}