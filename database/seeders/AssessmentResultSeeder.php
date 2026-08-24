<?php

namespace Database\Seeders;

use App\Models\Ref\AssessmentResult;
use Illuminate\Database\Seeder;

class AssessmentResultSeeder extends Seeder
{
    public function run(): void
    {
        $results = [
            [
                'code' => 'NORMAL',
                'name' => 'Normal',
                'severity' => 'success',
                'description' => 'Hasil assessment berada dalam rentang normal.',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'ABNORMAL',
                'name' => 'Abnormal',
                'severity' => 'danger',
                'description' => 'Hasil assessment berada di luar rentang normal dan memerlukan perhatian lebih lanjut.',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'PENDING',
                'name' => 'Pending',
                'severity' => 'warning',
                'description' => 'Assessment belum selesai atau hasil masih menunggu proses.',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'UNKNOWN',
                'name' => 'Unknown',
                'severity' => 'secondary',
                'description' => 'Hasil assessment tidak dapat ditentukan karena data tidak tersedia atau tidak mencukupi.',
                'display_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($results as $result) {
            AssessmentResult::updateOrCreate(
                ['code' => $result['code']],
                $result,
            );
        }
    }
}
