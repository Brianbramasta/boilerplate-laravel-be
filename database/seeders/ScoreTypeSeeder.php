<?php

namespace Database\Seeders;

use App\Models\Ref\ScoreType;
use Illuminate\Database\Seeder;

class ScoreTypeSeeder extends Seeder
{
    public function run(): void
    {
        ScoreType::updateOrCreate(
            ['code' => 'PERCENT'],
            ['name' => 'Persentase', 'symbol' => '%', 'is_active' => true],
        );
    }
}
