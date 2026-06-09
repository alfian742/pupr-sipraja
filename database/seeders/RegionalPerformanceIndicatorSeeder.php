<?php

namespace Database\Seeders;

use App\Models\RegionalPerformanceIndicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionalPerformanceIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = array_merge(
            // I. ASPEK GEOGRAFI DAN DEMOGRAFI
            $this->buildIndicatorRows(
                code: 'IKD-001',
                type: 'Aspek Geografi dan Demografi',
                name: 'Ketahanan Air - Rumah tangga yang memiliki akses terhadap layanan sumber air minum layak',
                unit: '%',
                baselineYear: 2024,
                baselineValue: null,
                targets: [
                    2025 => 94.87,
                    2026 => 95.81,
                    2027 => 96.77,
                    2028 => 97.75,
                    2029 => 98.73,
                    2030 => 98.93,
                ],
            ),
            $this->buildIndicatorRows(
                code: 'IKD-002',
                type: 'Aspek Geografi dan Demografi',
                name: 'Rumah tangga dengan akses sanitasi aman',
                unit: '%',
                baselineYear: 2024,
                baselineValue: 2.75,
                targets: [
                    2025 => 3.50,
                    2026 => 4.47,
                    2027 => 5.44,
                    2028 => 6.41,
                    2029 => 7.38,
                    2030 => 12.52,
                ],
            ),
            $this->buildIndicatorRows(
                code: 'IKD-003',
                type: 'Aspek Geografi dan Demografi',
                name: 'Konsumsi listrik per kapita',
                unit: 'kWh',
                baselineYear: 2024,
                baselineValue: 0.00,
                targets: [
                    2025 => 360.93,
                    2026 => 374.69,
                    2027 => 388.97,
                    2028 => 403.80,
                    2029 => 419.19,
                    2030 => 435.17,
                ],
            ),

            // II. ASPEK DAYA SAING DAERAH
            $this->buildIndicatorRows(
                code: 'IKD-004',
                type: 'Aspek Daya Saing Daerah',
                name: 'Indeks Kepuasan Layanan Infrastruktur (IKLI)',
                unit: 'Indeks',
                baselineYear: 2024,
                baselineValue: 54.34,
                targets: [
                    2025 => 56.71,
                    2026 => 58.98,
                    2027 => 61.74,
                    2028 => 64.64,
                    2029 => 68.47,
                    2030 => 70.03,
                ],
            ),
            $this->buildIndicatorRows(
                code: 'IKD-005',
                type: 'Aspek Daya Saing Daerah',
                name: 'Indeks Infrastruktur',
                unit: 'Angka',
                baselineYear: 2024,
                baselineValue: 55.45,
                targets: [
                    2025 => 56.35,
                    2026 => 58.55,
                    2027 => 60.63,
                    2028 => 63.18,
                    2029 => 65.86,
                    2030 => 69.46,
                ],
            ),
            $this->buildIndicatorRows(
                code: 'IKD-006',
                type: 'Aspek Daya Saing Daerah',
                name: 'Proporsi fasilitas pemerintah daerah yang menggunakan EBT',
                unit: '%',
                baselineYear: 2024,
                baselineValue: 0.11,
                targets: [
                    2025 => 0.76,
                    2026 => 1.51,
                    2027 => 2.70,
                    2028 => 3.89,
                    2029 => 5.08,
                    2030 => 6.26,
                ],
            ),

            // III. INDIKATOR KINERJA KUNCI
            $this->buildIndicatorRows(
                code: 'IKD-007',
                type: 'Indikator Kinerja Kunci',
                name: 'Tingkat Kemantapan Jalan',
                unit: 'Persentase',
                baselineYear: 2024,
                baselineValue: 74.00,
                targets: [
                    2025 => 75.00,
                    2026 => 76.786,
                    2027 => 77.281,
                    2028 => 78.144,
                    2029 => 79.501,
                    2030 => 81.166,
                ],
            ),
            $this->buildIndicatorRows(
                code: 'IKD-008',
                type: 'Indikator Kinerja Kunci',
                name: 'Persentase Kondisi Irigasi Kewenangan Kab/Kota',
                unit: '%',
                baselineYear: 2024,
                baselineValue: 82.00,
                targets: [
                    2025 => 85.00,
                    2026 => 88.69,
                    2027 => 92.38,
                    2028 => 96.07,
                    2029 => 99.76,
                    2030 => 100.00,
                ],
            ),
            $this->buildIndicatorRows(
                code: 'IKD-009',
                type: 'Indikator Kinerja Kunci',
                name: 'Persentase jumlah rumah tangga yang mendapatkan akses terhadap air minum melalui SPAM jaringan perpipaan dan bukan jaringan perpipaan terlindungi terhadap rumah tangga di seluruh kabupaten/kota',
                unit: '%',
                baselineYear: 2024,
                baselineValue: 78.82,
                targets: [
                    2025 => 80.59,
                    2026 => 81.32,
                    2027 => 82.05,
                    2028 => 82.78,
                    2029 => 83.51,
                    2030 => 84.24,
                ],
            ),
            $this->buildIndicatorRows(
                code: 'IKD-010',
                type: 'Indikator Kinerja Kunci',
                name: 'Persentase rumah tangga yang menempati hunian dengan akses air limbah domestik aman',
                unit: '%',
                baselineYear: 2024,
                baselineValue: 87.94,
                targets: [
                    2025 => 88.18,
                    2026 => 88.93,
                    2027 => 89.68,
                    2028 => 90.43,
                    2029 => 91.18,
                    2030 => 91.93,
                ],
            ),
            $this->buildIndicatorRows(
                code: 'IKD-011',
                type: 'Indikator Kinerja Kunci',
                name: 'Persentase kepatuhan PBG & SLF Kabupaten/Kota',
                unit: '%',
                baselineYear: 2024,
                baselineValue: 100.00,
                targets: [
                    2025 => 100.00,
                    2026 => 100.00,
                    2027 => 100.00,
                    2028 => 100.00,
                    2029 => 100.00,
                    2030 => 100.00,
                ],
            ),
        );

        RegionalPerformanceIndicator::insert($rows);
    }

    private function buildIndicatorRows(
        string $code,
        string $type,
        string $name,
        string $unit,
        int $baselineYear,
        ?float $baselineValue,
        array $targets,
        int $modifiedBy = 1
    ): array {
        $now = now();

        return collect($targets)
            ->map(function ($targetValue, $measurementYear) use (
                $code,
                $type,
                $name,
                $unit,
                $baselineYear,
                $baselineValue,
                $modifiedBy,
                $now
            ) {
                return [
                    'indicator_code'     => $code,
                    'indicator_type'     => $type,
                    'indicator_name'     => $name,
                    'indicator_unit'     => $unit,
                    'baseline_year'      => $baselineYear,
                    'baseline_value'     => $baselineValue,
                    'measurement_year'   => $measurementYear,
                    'target_value'       => $targetValue,
                    'achievement_value'  => null,
                    'performance_value'  => null,
                    'document_url'       => null,
                    'modified_by'        => $modifiedBy,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ];
            })
            ->values()
            ->all();
    }
}
