<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]+'),
            'name' => $this->faker->word() . ' Cuti',
            'is_deduct_balance' => true,
        ];
    }
}