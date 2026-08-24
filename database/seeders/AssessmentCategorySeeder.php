<?php

namespace Database\Seeders;

use App\Models\Master\AssessmentCategory;
use Illuminate\Database\Seeder;

class AssessmentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => 'KUALITAS', 'name' => 'Kualitas'],
            ['code' => 'KERAPIAN', 'name' => 'Kerapian'],
        ];

        foreach ($categories as $category) {
            AssessmentCategory::updateOrCreate(
                ['code' => $category['code']],
                $category + ['max_score' => 100, 'is_active' => true],
            );
        }
    }
}
