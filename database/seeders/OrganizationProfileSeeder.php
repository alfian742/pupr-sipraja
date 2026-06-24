<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrganizationProfile;
use App\Models\PersonnelProfile;
use Faker\Factory as Faker;

class OrganizationProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $organizationProfiles = [
            [
                'organization_about'      =>
                '<p>
                    DPUPR Kabupaten Lombok Tengah adalah perangkat daerah yang bertanggung jawab dalam
                    penyelenggaraan urusan pemerintahan di bidang pekerjaan umum dan penataan ruang di Kabupaten
                    Lombok Tengah.
                </p>
                <p>
                    DPUPR berperan dalam perencanaan, pembangunan, pemeliharaan, serta pengawasan
                    infrastruktur daerah, meliputi jalan dan jembatan, sumber daya air, permukiman, serta penataan
                    ruang. Dengan komitmen pada pelayanan publik yang profesional, transparan, dan berkelanjutan,
                    DPUPR mendukung terwujudnya pembangunan infrastruktur yang berkualitas demi meningkatkan
                    kesejahteraan masyarakat.
                </p>',
                'organization_structure'    => 'https://drive.google.com/file/d/1JlQJ05zoYfBWzRLEc2lAesm9AUQ9pA25/preview',
                'organization_structure_modified_by' => 1,
                'organization_structure_modified_at' => now(),
                'organization_summary'      => '<p>Mendukung pembangunan infrastruktur yang merata, berkelanjutan, dan memberi manfaat nyata bagi masyarakat Lombok Tengah, sejalan dengan visi kabupaten "MASMIRAH".</p>',
                'organization_vision'       => '<p>Terwujudnya penyediaan dan pengelolaan infrastruktur publik yang berkualitas, berkelanjutan, merata di seluruh wilayah, serta mendukung kesejahteraan dan kualitas hidup masyarakat Lombok Tengah.</p>',
                'organization_mission'      =>
                '<ul class="m-0 ps-3">
                    <li>Menyelenggarakan perencanaan dan pembangunan infrastruktur yang efektif, efisien, dan tepat
                        sasaran.</li>
                    <li>Meningkatkan kualitas dan pemerataan pelayanan publik di bidang pekerjaan umum dan penataan
                        ruang.</li>
                    <li>Mengelola sumber daya, aset, dan fasilitas publik secara profesional, transparan, dan
                        berkelanjutan.</li>
                    <li>Mendukung penataan ruang dan permukiman yang tertata, aman, dan nyaman bagi masyarakat.</li>
                    <li>Mendorong inovasi, teknologi, dan pembangunan berkelanjutan untuk mendukung pertumbuhan
                        daerah.</li>
                    <li>Meningkatkan koordinasi dan kolaborasi dengan seluruh pemangku kepentingan untuk pemerataan
                        pembangunan wilayah.</li>
                </ul>',
                'profile_modified_by' => 1,
                'profile_modified_at' => now(),
            ],
        ];

        foreach ($organizationProfiles as $row) {
            OrganizationProfile::create($row);
        }

        // $personnelProfiles = [
        //     [
        //         'personnel_name'        => 'Ir. Ahmad Santoso, M.T.',
        //         'personnel_position'    => 'Kepala Dinas',
        //         'personnel_description' => 'Bertanggung jawab atas pengelolaan dan pengawasan seluruh kegiatan pembangunan infrastruktur di Kabupaten Lombok Tengah.',
        //         'personnel_photo'       => null,
        //     ],
        //     [
        //         'personnel_name'        => 'Dra. Siti Nurhayati',
        //         'personnel_position'    => 'Sekretaris',
        //         'personnel_description' => 'Membantu Kepala Dinas dalam koordinasi, administrasi, dan pengelolaan program pembangunan di seluruh bidang.',
        //         'personnel_photo'       => null,
        //     ],
        //     [
        //         'personnel_name'        => 'Ir. Budi Raharjo',
        //         'personnel_position'    => 'Kepala Bidang Infrastruktur',
        //         'personnel_description' => 'Mengawasi pembangunan jalan, jembatan, dan fasilitas publik agar sesuai dengan standar teknis dan peraturan daerah.',
        //         'personnel_photo'       => null,
        //     ],
        // ];

        // $personnelPositionList = [
        //     'Kepala Bidang Bina Marga',
        //     'Kepala Bidang Cipta Karya',
        //     'Kepala Bidang Sumber Daya Air (SDA)',
        //     'Kepala Bidang Penataan Ruang',
        //     'Anggota Bidang Bina Marga',
        //     'Anggota Bidang Cipta Karya',
        //     'Anggota Bidang Sumber Daya Air (SDA)',
        //     'Anggota Bidang Penataan Ruang',
        // ];

        // // Generate 17 dummy personnel tambahan
        // for ($i = 0; $i < 17; $i++) {
        //     $personnelProfiles[] = [
        //         'personnel_name'        => $faker->name(),
        //         'personnel_position'    => $faker->randomElement($personnelPositionList),
        //         'personnel_description' => $faker->sentence(12),
        //         'personnel_photo'       => null,
        //         'modified_by' => 1,
        //     ];
        // }

        // // Insert semua data ke database
        // foreach ($personnelProfiles as $row) {
        //     PersonnelProfile::create($row);
        // }
    }
}
