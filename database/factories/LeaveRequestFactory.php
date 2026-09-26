<?php

namespace Database\Factories;

use App\Models\Employee\LeaveRequest;
use App\Models\Employee\LeaveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->optional()->dateTimeBetween('now', '+3 months');
        $endDate = $this->faker->optional()->dateTimeBetween($startDate, '+3 months + 7 days');

        return [
            'user_id' => User::factory(),
            'leave_type_id' => LeaveType::factory(),
            'start_date' => $startDate ? $startDate->format('Y-m-d') : now()->addDays(5)->format('Y-m-d'),
            'end_date' => $endDate ? $endDate->format('Y-m-d') : now()->addDays(7)->format('Y-m-d'),
            'total_days' => $this->faker->numberBetween(1, 10),
            'reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['SUBMITTED', 'APPROVED', 'REJECTED', 'CANCELLED']),
        ];
    }

    public function submitted(): self
    {
        return $this->state(['status' => LeaveRequest::STATUS_SUBMITTED]);
    }
}