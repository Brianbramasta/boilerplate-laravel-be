<?php

namespace Database\Seeders;

use App\Models\Master\Assessment;
use App\Models\Ref\AssessmentCode;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $psb = AssessmentCode::where('code', 'PSB')->firstOrFail();

        $names = [
            'Instalasi ODP',
            'Instalasi Customer',
            'Penarikan',
        ];

        foreach ($names as $name) {
            Assessment::updateOrCreate(
                ['name' => $name],
                ['assessment_code_id' => $psb->id, 'is_active' => true],
            );
        }
    }
}
