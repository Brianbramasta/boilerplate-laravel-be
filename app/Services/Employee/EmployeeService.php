<?php

namespace App\Services\Employee;

use App\Models\Employee\LeaveBalance;
use App\Models\Employee\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeService
{
    public function createLeaveRequest(array $data, int $userId): LeaveRequest
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $balance = LeaveBalance::where('user_id', $userId)
            ->where('leave_type_id', $data['leave_type_id'])
            ->where('year', $startDate->year)
            ->first();

        if (!$balance) {
            throw new HttpException(400, 'Saldo cuti tidak ditemukan.');
        }

        if ($totalDays > $balance->remaining_balance) {
            throw new HttpException(400, 'Saldo cuti tidak mencukupi.');
        }

        if ($this->hasOverlap($userId, $data['leave_type_id'], $startDate, $endDate)) {
            throw new HttpException(422, 'Periode cuti bentrok dengan pengajuan Anda yang lain.');
        }

        return DB::transaction(function () use ($data, $userId, $totalDays) {
            return LeaveRequest::create([
                'user_id' => $userId,
                'leave_type_id' => $data['leave_type_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'total_days' => $totalDays,
                'reason' => $data['reason'],
                'status' => LeaveRequest::STATUS_SUBMITTED,
            ]);
        });
    }

    private function hasOverlap(int $userId, int $leaveTypeId, Carbon $startDate, Carbon $endDate): bool
    {
        return LeaveRequest::where('user_id', $userId)
            ->where('status', '!=', LeaveRequest::STATUS_CANCELLED)
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->exists();
    }
}