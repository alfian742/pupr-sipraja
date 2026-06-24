<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\DownloadCenter;
use App\Models\FAQ;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OtherInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // FAQ
        $faq = [
            [
                'faq_question' => 'Bagaimana cara mengurus perizinan pembangunan di Lombok Tengah?',
                'faq_answer' => 'Anda dapat mengurus perizinan melalui aplikasi online atau langsung ke kantor Dinas PUPR. Pastikan menyiapkan dokumen lengkap sesuai jenis izin yang diajukan.',
            ],
            [
                'faq_question' => 'Apa saja persyaratan untuk konsultasi teknis PUPR?',
                'faq_answer' => 'Persyaratan meliputi dokumen rencana pembangunan, peta lokasi, dan dokumen pendukung lainnya sesuai bidang teknis yang dikonsultasikan.',
            ],
            [
                'faq_question' => 'Dimana saya bisa mengakses standar pelayanan publik PUPR?',
                'faq_answer' => 'Standar pelayanan publik dapat diakses melalui website resmi Dinas PUPR Lombok Tengah atau secara langsung di kantor untuk mendapatkan SPP, maklumat, dan SOP.',
            ],
        ];

        // Generate 27 FAQ dummy tambahan
        // for ($i = 0; $i < 27; $i++) {
        //     $faq[] = [
        //         'faq_question' => $faker->sentence(6, true) . '?', // pertanyaan acak
        //         'faq_answer'   => $faker->paragraph(2, true),      // jawaban acak 2 kalimat
        //         'modified_by'  => 1,
        //     ];
        // }

        // Insert semua data ke database
        foreach ($faq as $row) {
            FAQ::create($row);
        }

        // Pusat Unduhan
        // for ($i = 0; $i < 100; $i++) {

        //     $title = $faker->unique()->sentence(7);

        //     DownloadCenter::create([
        //         'document_title' => $title,
        //         'document_type' => $title,
        //         'slug' => Str::slug($title),
        //         'description' => collect($faker->paragraphs(rand(8, 15))) // 8–15 paragraf acak
        //             ->map(fn($p) => "<p>$p</p>")
        //             ->implode(''),
        //         'document_url' => 'https://drive.google.com/file/d/1JlQJ05zoYfBWzRLEc2lAesm9AUQ9pA25/preview',
        //         'status' => $faker->randomElement(['draft', 'publish', 'archive', 'internal']),
        //         'modified_by' => 1,
        //     ]);
        // }

        // Kontak
        $contact = [
            'email'  => 'dpuloteng@gmail.com',
            'email_alternative' => null,
            'phone_number' => '(0370) 654393',
            'phone_number_alternative' => null,
            'whatsapp_number' => '081234567890',
            'whatsapp_number_alternative' => null,
            'operational_time' => '08:00-16:00 WITA',
            'address' => 'Komplek Pusat Pemerintahan Kab. Lombok Tengah, Gedung A Lantai 4, Jl. Raden Puguh, Praya, NTB',
            'google_maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3944.0162030221904!2d116.24932432340832!3d-8.690008290190438!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcdb1f96660c3e3%3A0x72f79c0857459bce!2sDinas%20PUPR%20Kabupaten%20Lombok%20Tengah!5e0!3m2!1sid!2sid!4v1772075700747!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'facebook_url' => 'https://www.facebook.com/',
            'instagram_url' => 'https://www.instagram.com/',
            'twitter_url' => null,
            'tiktok_url' => null,
            'youtube_url' => 'https://www.youtube.com/',
            'modified_by' => 1,
        ];

        Contact::create($contact);
    }
}
