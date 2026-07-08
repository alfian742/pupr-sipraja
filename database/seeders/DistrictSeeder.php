<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            [
                'id' => 1,
                'district_name' => 'PRAYA BARAT',
                'bps_code' => '5202010',
                'kemendagri_code' => '52.02.05',
                'resident_count' => 89899
            ],
            [
                'id' => 2,
                'district_name' => 'PRAYA BARAT DAYA',
                'bps_code' => '5202011',
                'kemendagri_code' => '52.02.11',
                'resident_count' => 64165
            ],
            [
                'id' => 3,
                'district_name' => 'PUJUT',
                'bps_code' => '5202020',
                'kemendagri_code' => '52.02.04',
                'resident_count' => 127840
            ],
            [
                'id' => 4,
                'district_name' => 'PRAYA TIMUR',
                'bps_code' => '5202030',
                'kemendagri_code' => '52.02.06',
                'resident_count' => 77011
            ],
            [
                'id' => 5,
                'district_name' => 'JANAPRIA',
                'bps_code' => '5202040',
                'kemendagri_code' => '52.02.07',
                'resident_count' => 92793
            ],
            [
                'id' => 6,
                'district_name' => 'KOPANG',
                'bps_code' => '5202050',
                'kemendagri_code' => '52.02.09',
                'resident_count' => 101379
            ],
            [
                'id' => 7,
                'district_name' => 'PRAYA',
                'bps_code' => '5202060',
                'kemendagri_code' => '52.02.01',
                'resident_count' => 136064
            ],
            [
                'id' => 8,
                'district_name' => 'PRAYA TENGAH',
                'bps_code' => '5202061',
                'kemendagri_code' => '52.02.10',
                'resident_count' => 79721
            ],
            [
                'id' => 9,
                'district_name' => 'JONGGAT',
                'bps_code' => '5202070',
                'kemendagri_code' => '52.02.02',
                'resident_count' => 114808
            ],
            [
                'id' => 10,
                'district_name' => 'PRINGGARATA',
                'bps_code' => '5202080',
                'kemendagri_code' => '52.02.08',
                'resident_count' => 82430
            ],
            [
                'id' => 11,
                'district_name' => 'BATUKLIANG',
                'bps_code' => '5202090',
                'kemendagri_code' => '52.02.03',
                'resident_count' => 96752
            ],
            [
                'id' => 12,
                'district_name' => 'BATUKLIANG UTARA',
                'bps_code' => '5202091',
                'kemendagri_code' => '52.02.12',
                'resident_count' => 65854
            ],
        ];

        foreach ($districts as $district) {
            District::unguarded(function () use ($district) {
                District::create($district);
            });
        }
    }
}
