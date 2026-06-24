<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'department_name' => 'Bina Marga',
                'slug' => 'bina-marga',
                'description' => 'Perencanaan, pembangunan, dan pemeliharaan jalan serta jembatan kabupaten untuk meningkatkan konektivitas dan kelancaran transportasi.',
                'logo' => null,
                'modified_by' => 1
            ],
            [
                'department_name' => 'Cipta Karya',
                'slug' => 'cipta-karya',
                'description' => 'Pengelolaan dan pembangunan infrastruktur permukiman, gedung pemerintah, serta sarana dan prasarana dasar untuk mendukung kualitas lingkungan.',
                'logo' => null,
                'modified_by' => 1
            ],
            [
                'department_name' => 'Sumber Daya Air (SDA)',
                'slug' => 'sumber-daya-air-sda',
                'description' => 'Pengelolaan dan pemeliharaan jaringan irigasi, pengendalian banjir, serta konservasi sumber daya air untuk mendukung ketahanan air daerah.',
                'logo' => null,
                'modified_by' => 1
            ],
            [
                'department_name' => 'Penataan Ruang',
                'slug' => 'penataan-ruang',
                'description' => 'Perencanaan dan pengendalian tata ruang wilayah untuk mewujudkan pembangunan yang tertib, berkelanjutan, dan berwawasan lingkungan.',
                'logo' => null,
                'modified_by' => 1
            ],
            [
                'department_name' => 'Sekretariat',
                'slug' => 'sekretariat',
                'description' => 'Mengoordinasikan administrasi umum, perencanaan program, keuangan, kepegawaian, serta layanan internal untuk mendukung kelancaran tugas seluruh bidang.',
                'logo' => null,
                'modified_by' => 1
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
