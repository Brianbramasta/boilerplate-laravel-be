<?php

namespace Database\Seeders;

use App\Models\Ref\AssessmentCode;
use Illuminate\Database\Seeder;

class AssessmentCodeSeeder extends Seeder
{
    public function run(): void
    {
        AssessmentCode::updateOrCreate(
            ['code' => 'PSB'],
            ['name' => 'PSB'],
        );
    }
}
