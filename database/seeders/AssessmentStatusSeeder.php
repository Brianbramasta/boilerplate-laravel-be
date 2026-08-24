<?php

namespace Database\Seeders;

use App\Models\Ref\AssessmentStatus;
use Illuminate\Database\Seeder;

class AssessmentStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['code' => 'DRAFT', 'name' => 'Draft', 'is_final' => false],
            ['code' => 'IN_PROGRESS', 'name' => 'Sedang Diproses', 'is_final' => false],
            ['code' => 'COMPLETE', 'name' => 'Selesai', 'is_final' => true],
            ['code' => 'WAITING', 'name' => 'Menunggu Validasi', 'is_final' => false],
        ];

        foreach ($statuses as $status) {
            AssessmentStatus::updateOrCreate(
                ['code' => $status['code']],
                $status + ['is_active' => true],
            );
        }
    }
}
