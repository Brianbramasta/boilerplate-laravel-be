<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            MenuSeeder::class,
            RoleMenuSeeder::class,
            UserSeeder::class,
            AssessmentCodeSeeder::class,
            AssessmentResultSeeder::class,
            AssessmentStatusSeeder::class,
            CriteriaTypeSeeder::class,
            CalculationOperationSeeder::class,
            ScoreTypeSeeder::class,
            AssessmentCategorySeeder::class,
            AssessmentSeeder::class,
        ]);
    }
}
