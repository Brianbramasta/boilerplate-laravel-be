<?php

namespace Database\Seeders;

use App\Models\Ref\CalculationOperation;
use Illuminate\Database\Seeder;

class CalculationOperationSeeder extends Seeder
{
    public function run(): void
    {
        $operations = [
            ['code' => 'ADDITION', 'symbol' => '+', 'name' => 'Penambahan'],
            ['code' => 'SUBTRACTION', 'symbol' => '-', 'name' => 'Pengurangan'],
        ];

        foreach ($operations as $operation) {
            CalculationOperation::updateOrCreate(
                ['code' => $operation['code']],
                $operation + ['is_active' => true],
            );
        }
    }
}
