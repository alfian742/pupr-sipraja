<?php

namespace Database\Seeders;

use App\Models\PublicInformationPortal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PublicInformationPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portals = [
            [
                'portal_name' => 'Website Resmi Pemerintah Kabupaten Lombok Tengah',
                'description' => 'Portal utama Pemerintah Kabupaten Lombok Tengah yang menyajikan informasi pemerintahan, berita daerah, pengumuman, layanan publik, serta tautan menuju kanal informasi resmi pemerintah daerah.',
                'website_url' => 'https://lomboktengahkab.go.id/',
            ],
            [
                'portal_name' => 'PPID',
                'description' => 'Portal Pejabat Pengelola Informasi dan Dokumentasi Kabupaten Lombok Tengah yang menyediakan layanan informasi publik, daftar informasi publik, permohonan informasi, pengajuan keberatan, regulasi, dan maklumat pelayanan.',
                'website_url' => 'https://ppid.lomboktengahkab.go.id/',
            ],
            [
                'portal_name' => 'Satu Data',
                'description' => 'Portal data sektoral Pemerintah Kabupaten Lombok Tengah yang digunakan sebagai media publikasi, integrasi, dan pemanfaatan data daerah secara terbuka, terstandar, dan dapat dipertanggungjawabkan.',
                'website_url' => 'https://data.lomboktengahkab.go.id/',
            ],
            [
                'portal_name' => 'JDIH',
                'description' => 'Portal Jaringan Dokumentasi dan Informasi Hukum Kabupaten Lombok Tengah yang menyediakan dokumen hukum daerah, produk hukum, peraturan daerah, peraturan bupati, serta informasi hukum lainnya.',
                'website_url' => 'https://jdih.lomboktengahkab.go.id/',
            ],
            [
                'portal_name' => 'SP4N LAPOR',
                'description' => 'Kanal resmi penyampaian aspirasi dan pengaduan masyarakat kepada Pemerintah Kabupaten Lombok Tengah melalui sistem pengelolaan pengaduan pelayanan publik nasional.',
                'website_url' => 'https://www.lapor.go.id/instansi/pemerintah-kabupaten-lombok-tengah',
            ],
            [
                'portal_name' => 'LPSE',
                'description' => 'Portal layanan pengadaan secara elektronik Kabupaten Lombok Tengah yang menyajikan informasi tender, non-tender, pengumuman pengadaan, daftar hitam, serta informasi pengadaan barang dan jasa pemerintah.',
                'website_url' => 'https://spse.inaproc.id/lomboktengahkab',
            ],
            [
                'portal_name' => 'Badan Pusat Statistik',
                'description' => 'Portal resmi BPS Kabupaten Lombok Tengah yang menyediakan data statistik, publikasi daerah, indikator strategis, tabel statistik, dan informasi statistik sektoral untuk kebutuhan perencanaan dan analisis pembangunan.',
                'website_url' => 'https://lomboktengahkab.bps.go.id/',
            ],
            [
                'portal_name' => 'PPID BPS',
                'description' => 'Portal PPID BPS Kabupaten Lombok Tengah yang menyediakan layanan informasi publik statistik, informasi berkala, informasi setiap saat, serta informasi serta-merta sesuai ketentuan keterbukaan informasi publik.',
                'website_url' => 'https://ppid.bps.go.id/?mfd=5202',
            ],
            [
                'portal_name' => 'Data Indonesia - Lombok Tengah',
                'description' => 'Halaman agregasi data Kabupaten Lombok Tengah pada Portal Satu Data Indonesia yang menampilkan dataset terbuka terkait berbagai sektor pembangunan daerah.',
                'website_url' => 'https://data.go.id/instantion/kabupaten-lombok-tengah',
            ],
            [
                'portal_name' => 'Data IPKD',
                'description' => 'Halaman informasi Indeks Pengelolaan Keuangan Daerah Kabupaten Lombok Tengah yang memuat data dan dokumen terkait transparansi serta penilaian pengelolaan keuangan daerah.',
                'website_url' => 'https://lomboktengahkab.go.id/halaman/indeks-pengelolaan-keuangan-daerah-tahun-2025',
            ],
            [
                'portal_name' => 'Dinas Komunikasi dan Informatika',
                'description' => 'Portal Diskominfo Kabupaten Lombok Tengah yang menyajikan informasi komunikasi publik, berita perangkat daerah, layanan informatika, statistik sektoral, persandian, dan pengelolaan informasi pemerintahan daerah.',
                'website_url' => 'https://diskominfo.lomboktengahkab.go.id/',
            ],
            [
                'portal_name' => 'Mall Pelayanan Publik',
                'description' => 'Portal informasi layanan publik terpadu Kabupaten Lombok Tengah yang mendukung akses masyarakat terhadap layanan perizinan, nonperizinan, dan layanan administrasi pemerintahan lainnya.',
                'website_url' => 'https://mpp.lomboktengahkab.go.id/',
            ],
            [
                'portal_name' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'description' => 'Portal layanan administrasi kependudukan Kabupaten Lombok Tengah yang menyediakan informasi pelayanan dokumen kependudukan, pencatatan sipil, persyaratan layanan, dan pengumuman layanan adminduk.',
                'website_url' => 'https://disdukcapil.lomboktengahkab.go.id/',
            ],
            [
                'portal_name' => 'Badan Pengelolaan Pendapatan Daerah',
                'description' => 'Portal informasi pendapatan daerah Kabupaten Lombok Tengah yang menyajikan informasi pajak daerah, retribusi, layanan wajib pajak, dan informasi pengelolaan pendapatan asli daerah.',
                'website_url' => 'https://bapenda.lomboktengahkab.go.id/',
            ],
            [
                'portal_name' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'description' => 'Portal informasi bidang pekerjaan umum dan penataan ruang Kabupaten Lombok Tengah yang menyajikan informasi infrastruktur, sumber daya air, jalan, bangunan, tata ruang, dan layanan terkait bidang PUPR.',
                'website_url' => 'https://dpupr.lomboktengahkab.go.id/',
            ],
        ];

        foreach ($portals as $portal) {
            PublicInformationPortal::updateOrCreate(
                [
                    'portal_name' => $portal['portal_name'],
                ],
                [
                    'slug'        => Str::slug($portal['portal_name']),
                    'description' => $portal['description'],
                    'website_url' => $portal['website_url'],
                    'logo'        => null,
                    'modified_by' => null,
                ]
            );
        }
    }
}
