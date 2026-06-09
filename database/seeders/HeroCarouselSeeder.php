<?php

namespace Database\Seeders;

use App\Models\HeroCarousel;
use Illuminate\Database\Seeder;

class HeroCarouselSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        $carousels = [
            [
                'title' => 'Foto DPUPR Kabupaten Lombok Tengah',
                'description' => 'Tampilan gedung dan lingkungan DPUPR Kabupaten Lombok Tengah.',
                'image_path' => 'public/landing/img/hero.jpg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Kegiatan Infrastruktur DPUPR',
                'description' => 'Dokumentasi kegiatan pembangunan infrastruktur daerah.',
                'image_path' => 'public/landing/img/hero.jpg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Layanan DPUPR Kabupaten Lombok Tengah',
                'description' => 'Informasi layanan publik bidang pekerjaan umum dan penataan ruang.',
                'image_path' => 'public/landing/img/hero.jpg',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($carousels as $carousel) {
            HeroCarousel::updateOrCreate(
                ['image_path' => $carousel['image_path']],
                $carousel
            );
        }
    }
}
