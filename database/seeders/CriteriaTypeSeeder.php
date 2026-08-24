<?php

namespace Database\Seeders;

use App\Models\Ref\CriteriaType;
use Illuminate\Database\Seeder;

class CriteriaTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['code' => 'SINGLE', 'name' => 'Single'],
            ['code' => 'CALCULATE', 'name' => 'Kalkulasi'],
        ];

        foreach ($types as $type) {
            CriteriaType::updateOrCreate(
                ['code' => $type['code']],
                $type + ['is_active' => true],
            );
        }
    }
}
