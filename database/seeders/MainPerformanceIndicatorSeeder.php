<?php

namespace Database\Seeders;

use App\Models\MainPerformanceIndicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MainPerformanceIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modifiedBy = 1;

        $indicators = [
            [
                'indicator_code' => 'IKU-001',
                'indicator_name' => 'Indeks Kepuasan Layanan Infrastruktur (IKLI)',
                'indicator_unit' => 'Indeks',
                'baseline_year' => 2024,
                'baseline_value' => 54.34,
                'targets' => [
                    2025 => 56.71,
                    2026 => 58.98,
                    2027 => 61.74,
                    2028 => 64.64,
                    2029 => 68.47,
                    2030 => 70.03,
                ],
            ],
            [
                'indicator_code' => 'IKU-002',
                'indicator_name' => 'Indeks Infrastruktur',
                'indicator_unit' => 'Angka',
                'baseline_year' => 2024,
                'baseline_value' => 55.45,
                'targets' => [
                    2025 => 56.35,
                    2026 => 58.55,
                    2027 => 60.63,
                    2028 => 63.18,
                    2029 => 65.86,
                    2030 => 69.46,
                ],
            ],
            [
                'indicator_code' => 'IKU-003',
                'indicator_name' => 'Persentase Kesesuaian Pemanfaatan Ruang',
                'indicator_unit' => '%',
                'baseline_year' => 2024,
                'baseline_value' => 100.00,
                'targets' => [
                    2025 => 100.00,
                    2026 => 100.00,
                    2027 => 100.00,
                    2028 => 100.00,
                    2029 => 100.00,
                    2030 => 100.00,
                ],
            ],
            [
                'indicator_code' => 'IKU-004',
                'indicator_name' => 'Nilai Implementasi SAKIP Perangkat Daerah',
                'indicator_unit' => 'Poin',
                'baseline_year' => 2024,
                'baseline_value' => 66.84,
                'targets' => [
                    2025 => 72.00,
                    2026 => 73.04,
                    2027 => 75.56,
                    2028 => 77.03,
                    2029 => 79.15,
                    2030 => 80.12,
                ],
            ],
        ];

        DB::transaction(function () use ($indicators, $modifiedBy) {
            foreach ($indicators as $indicator) {
                foreach ($indicator['targets'] as $measurementYear => $targetValue) {
                    MainPerformanceIndicator::updateOrCreate(
                        [
                            'indicator_name' => $indicator['indicator_name'],
                            'measurement_year' => $measurementYear,
                        ],
                        [
                            'indicator_code' => $indicator['indicator_code'],
                            'indicator_unit' => $indicator['indicator_unit'],
                            'baseline_year' => $indicator['baseline_year'],
                            'baseline_value' => $indicator['baseline_value'],
                            'target_value' => $targetValue,
                            'achievement_value' => null,
                            'performance_value' => null,
                            'document_url' => null,
                            'modified_by' => $modifiedBy,
                        ]
                    );
                }
            }
        });
    }
}
