<?php

namespace Database\Seeders;

use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->first();

        if (!$user) {
            $user = User::create([
                'name'              => 'Administrator',
                'email'             => 'admin@example.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'is_active'         => true,
                'role'              => 'admin',
            ]);
        }

        $uncategorizedCategory = BlogCategory::updateOrCreate(
            [
                'slug' => 'tak-berkategori',
            ],
            [
                'created_by'  => $user->id,
                'updated_by'  => $user->id,
                'name'        => 'Tak Berkategori',
                'description' => 'Kategori default untuk artikel yang belum memiliki kategori khusus.',
                'is_active'   => true,
                'sort_order'  => 0,
            ]
        );

        $publicServiceCategory = BlogCategory::updateOrCreate(
            [
                'slug' => 'layanan-publik',
            ],
            [
                'created_by'  => $user->id,
                'updated_by'  => $user->id,
                'name'        => 'Layanan Publik',
                'description' => 'Kategori artikel yang membahas informasi layanan publik, prosedur, persyaratan, dan standar pelayanan.',
                'is_active'   => true,
                'sort_order'  => 1,
            ]
        );

        $serviceArticles = [
            [
                'title' => 'Perizinan dan Nonperizinan',
                'excerpt' => 'Informasi mengenai tata cara pengajuan layanan, persyaratan, alur proses, serta dokumen yang dibutuhkan dalam pelayanan publik bidang PUPR.',
                'content' => <<<HTML
<article>
    <p>
        Layanan <strong>Perizinan dan Nonperizinan</strong> merupakan sarana informasi bagi masyarakat
        untuk mengetahui tata cara pengajuan layanan, persyaratan, alur proses, serta dokumen yang
        dibutuhkan
        dalam pelayanan publik bidang PUPR.
    </p>

    <h3>Deskripsi Layanan</h3>
    <p>
        Layanan ini bertujuan untuk memberikan kemudahan akses informasi dan transparansi kepada
        masyarakat
        dalam proses pengajuan berbagai layanan yang termasuk dalam kategori perizinan maupun
        nonperizinan.
        Setiap jenis layanan memiliki ketentuan dan persyaratan yang berbeda sesuai dengan regulasi yang
        berlaku.
    </p>

    <h3>Ruang Lingkup Layanan</h3>
    <ul>
        <li>Informasi persyaratan administrasi dan teknis</li>
        <li>Informasi alur atau prosedur pengajuan layanan</li>
        <li>Informasi formulir dan dokumen pendukung</li>
        <li>Informasi estimasi waktu penyelesaian layanan</li>
    </ul>

    <h3>Persyaratan Umum</h3>
    <ul>
        <li>Surat permohonan</li>
        <li>Identitas pemohon (KTP atau dokumen lain yang sah)</li>
        <li>Dokumen kepemilikan atau legalitas objek</li>
        <li>Dokumen teknis sesuai jenis layanan</li>
    </ul>

    <h3>Prosedur Pelayanan</h3>
    <ol>
        <li>Pemohon menyiapkan seluruh dokumen persyaratan.</li>
        <li>Pemohon mengajukan permohonan melalui sistem atau loket pelayanan.</li>
        <li>Petugas melakukan verifikasi administrasi.</li>
        <li>Jika diperlukan, dilakukan verifikasi teknis atau peninjauan lapangan.</li>
        <li>Hasil layanan diterbitkan atau disampaikan kepada pemohon.</li>
    </ol>

    <h3>Waktu Pelayanan</h3>
    <p>
        Estimasi waktu pelayanan bervariasi tergantung pada jenis layanan yang diajukan,
        dengan rata-rata waktu penyelesaian antara <strong>5 sampai 14 hari kerja</strong>.
    </p>

    <h3>Biaya</h3>
    <p>
        Biaya pelayanan mengikuti ketentuan peraturan daerah yang berlaku.
        Beberapa layanan dapat diberikan secara <strong>gratis</strong>, sementara layanan tertentu
        dikenakan retribusi sesuai dengan ketentuan yang berlaku.
    </p>

    <h3>Formulir dan Dokumen Pendukung</h3>
    <ul>
        <li>Formulir permohonan layanan</li>
        <li>Dokumen teknis (gambar, peta, atau dokumen pendukung lainnya)</li>
        <li>Dokumen legalitas kepemilikan</li>
    </ul>

    <h3>Catatan Penting</h3>
    <p>
        Setiap jenis layanan memiliki persyaratan dan prosedur yang berbeda.
        Masyarakat disarankan untuk membaca informasi layanan secara lengkap sebelum mengajukan
        permohonan.
    </p>

    <h3>Kontak Layanan</h3>
    <p>
        Untuk informasi lebih lanjut, masyarakat dapat menghubungi petugas pelayanan melalui:
    </p>
    <ul>
        <li>Telepon: (0370) 654393</li>
        <li>Email: dpuloteng@gmail.com</li>
        <li>Jam Pelayanan: Senin - Jumat, 08.00 - 16.00 WITA</li>
    </ul>
</article>
HTML,
            ],
            [
                'title' => 'Konsultasi Teknis',
                'excerpt' => 'Layanan pendampingan, arahan, serta solusi teknis kepada masyarakat, pengembang, maupun instansi dalam perencanaan dan pelaksanaan kegiatan bidang PUPR.',
                'content' => <<<HTML
<article>
    <p>
        Layanan <strong>Konsultasi Teknis</strong> merupakan layanan yang disediakan untuk memberikan
        pendampingan, arahan, serta solusi teknis kepada masyarakat, pengembang, maupun instansi
        dalam perencanaan dan pelaksanaan kegiatan di bidang PUPR.
    </p>

    <h3>Deskripsi Layanan</h3>
    <p>
        Layanan ini bertujuan untuk membantu pemohon dalam memahami aspek teknis terkait pekerjaan
        infrastruktur, bangunan, sumber daya air, dan sistem jaringan, sehingga perencanaan dan pelaksanaan
        kegiatan dapat berjalan sesuai dengan standar dan ketentuan yang berlaku.
    </p>

    <h3>Ruang Lingkup Konsultasi</h3>
    <ul>
        <li>Konsultasi perencanaan bangunan gedung dan infrastruktur</li>
        <li>Konsultasi teknis jaringan air minum (SPAM)</li>
        <li>Konsultasi drainase dan pengendalian banjir</li>
        <li>Konsultasi jalan dan jembatan</li>
        <li>Konsultasi gambar teknis dan dokumen perencanaan</li>
    </ul>

    <h3>Persyaratan Pengajuan</h3>
    <ul>
        <li>Surat permohonan konsultasi</li>
        <li>Identitas pemohon</li>
        <li>Dokumen atau gambar teknis yang akan dikonsultasikan</li>
        <li>Data pendukung lainnya sesuai kebutuhan konsultasi</li>
    </ul>

    <h3>Prosedur Pelayanan</h3>
    <ol>
        <li>Pemohon mengajukan permohonan konsultasi.</li>
        <li>Petugas melakukan verifikasi awal terhadap permohonan.</li>
        <li>Penjadwalan sesi konsultasi teknis.</li>
        <li>Pelaksanaan konsultasi bersama tenaga teknis.</li>
        <li>Pemberian rekomendasi atau arahan teknis.</li>
    </ol>

    <h3>Waktu Pelayanan</h3>
    <p>
        Pelayanan konsultasi dilakukan sesuai dengan jadwal yang telah ditentukan,
        dengan estimasi waktu penanganan antara <strong>1 sampai 5 hari kerja</strong>
        tergantung kompleksitas permasalahan yang dikonsultasikan.
    </p>

    <h3>Biaya</h3>
    <p>
        Layanan konsultasi teknis pada umumnya diberikan secara <strong>gratis</strong>.
        Namun untuk konsultasi lanjutan yang memerlukan kajian mendalam, dapat dikenakan
        biaya sesuai dengan ketentuan yang berlaku.
    </p>

    <h3>Manfaat Layanan</h3>
    <ul>
        <li>Mendapatkan arahan teknis yang tepat sebelum pelaksanaan pekerjaan</li>
        <li>Mengurangi risiko kesalahan perencanaan</li>
        <li>Meningkatkan kualitas hasil pembangunan</li>
        <li>Memastikan kesesuaian dengan regulasi yang berlaku</li>
    </ul>

    <h3>Catatan Penting</h3>
    <p>
        Pemohon disarankan untuk membawa dokumen teknis secara lengkap agar proses konsultasi
        dapat berjalan lebih efektif dan menghasilkan rekomendasi yang optimal.
    </p>

    <h3>Kontak Layanan</h3>
    <p>
        Untuk informasi lebih lanjut atau penjadwalan konsultasi, dapat menghubungi:
    </p>
    <ul>
        <li>Telepon: (0370) 654393</li>
        <li>Email: dpuloteng@gmail.com</li>
        <li>Jam Pelayanan: Senin - Jumat, 08.00 - 16.00 WITA</li>
    </ul>
</article>
HTML,
            ],
            [
                'title' => 'Standar Pelayanan Publik',
                'excerpt' => 'Acuan penyelenggaraan pelayanan publik yang mencakup prosedur, persyaratan, waktu pelayanan, biaya, serta mekanisme pengaduan.',
                'content' => <<<HTML
<article>
    <p>
        <strong>Standar Pelayanan Publik</strong> merupakan acuan yang digunakan oleh penyelenggara
        layanan dalam memberikan pelayanan kepada masyarakat secara transparan, akuntabel, dan berkualitas.
        Standar ini mencakup prosedur, persyaratan, waktu pelayanan, biaya, serta mekanisme pengaduan.
    </p>

    <h3>Deskripsi Layanan</h3>
    <p>
        Standar Pelayanan Publik disusun sebagai bentuk komitmen pemerintah dalam memberikan kepastian
        pelayanan kepada masyarakat. Dokumen ini menjadi pedoman bagi petugas pelayanan dan juga sebagai
        informasi bagi masyarakat mengenai hak dan kewajiban dalam memperoleh layanan.
    </p>

    <h3>Ruang Lingkup</h3>
    <ul>
        <li>Standar Pelayanan (SPP)</li>
        <li>Maklumat Pelayanan</li>
        <li>Standar Operasional Prosedur (SOP)</li>
        <li>Hak dan kewajiban pengguna layanan</li>
        <li>Mekanisme pengaduan dan evaluasi layanan</li>
    </ul>

    <h3>Komponen Standar Pelayanan</h3>
    <ul>
        <li>Dasar hukum pelayanan</li>
        <li>Persyaratan layanan</li>
        <li>Sistem, mekanisme, dan prosedur</li>
        <li>Jangka waktu penyelesaian</li>
        <li>Biaya atau tarif pelayanan</li>
        <li>Produk layanan</li>
        <li>Sarana dan prasarana</li>
        <li>Kompetensi pelaksana</li>
    </ul>

    <h3>Maklumat Pelayanan</h3>
    <p>
        Maklumat pelayanan merupakan pernyataan tertulis yang berisi komitmen penyelenggara layanan
        untuk memberikan pelayanan sesuai dengan standar yang telah ditetapkan serta kesediaan menerima
        sanksi apabila tidak memenuhi standar tersebut.
    </p>

    <h3>Prosedur Pelayanan</h3>
    <ol>
        <li>Masyarakat mengajukan permohonan layanan.</li>
        <li>Petugas melakukan verifikasi dan validasi dokumen.</li>
        <li>Pelaksanaan pelayanan sesuai SOP.</li>
        <li>Penyampaian hasil layanan kepada pemohon.</li>
    </ol>

    <h3>Waktu dan Biaya</h3>
    <p>
        Waktu dan biaya pelayanan ditetapkan sesuai dengan standar masing-masing jenis layanan.
        Informasi tersebut disampaikan secara terbuka agar masyarakat dapat mengetahui estimasi
        waktu penyelesaian dan biaya yang diperlukan.
    </p>

    <h3>Hak dan Kewajiban</h3>
    <p><strong>Hak Masyarakat:</strong></p>
    <ul>
        <li>Mendapatkan pelayanan yang berkualitas</li>
        <li>Mendapatkan informasi yang jelas dan transparan</li>
        <li>Menyampaikan pengaduan atas pelayanan</li>
    </ul>

    <p><strong>Kewajiban Masyarakat:</strong></p>
    <ul>
        <li>Memenuhi persyaratan yang telah ditetapkan</li>
        <li>Mengikuti prosedur pelayanan</li>
        <li>Memberikan data yang benar dan valid</li>
    </ul>

    <h3>Mekanisme Pengaduan</h3>
    <p>
        Masyarakat dapat menyampaikan pengaduan terkait pelayanan melalui berbagai saluran yang tersedia.
        Setiap pengaduan akan ditindaklanjuti sesuai dengan mekanisme yang berlaku untuk meningkatkan
        kualitas pelayanan.
    </p>

    <ul>
        <li>Pengaduan langsung ke unit pelayanan</li>
        <li>Melalui telepon atau email resmi</li>
        <li>Melalui sistem pengaduan online</li>
    </ul>

    <h3>Catatan Penting</h3>
    <p>
        Standar Pelayanan Publik ini bersifat dinamis dan dapat diperbarui sesuai dengan perkembangan
        kebijakan dan kebutuhan pelayanan masyarakat.
    </p>

    <h3>Kontak Layanan</h3>
    <p>
        Untuk informasi lebih lanjut mengenai standar pelayanan, dapat menghubungi:
    </p>
    <ul>
        <li>Telepon: (0370) 654393</li>
        <li>Email: dpuloteng@gmail.com</li>
        <li>Jam Pelayanan: Senin - Jumat, 08.00 - 16.00 WITA</li>
    </ul>
</article>
HTML,
            ],
        ];

        foreach ($serviceArticles as $index => $item) {
            BlogArticle::updateOrCreate(
                [
                    'slug' => Str::slug($item['title']),
                ],
                [
                    'user_id'          => $user->id,
                    'blog_category_id' => $publicServiceCategory->id,
                    'created_by'       => $user->id,
                    'updated_by'       => $user->id,
                    'title'            => $item['title'],
                    'excerpt'          => $item['excerpt'],
                    'content'          => $item['content'],
                    'thumbnail'        => null,
                    'status'           => BlogArticle::STATUS_PUBLISHED,
                    'is_featured'      => true,
                    'views_count'      => random_int(50, 500),
                    'published_at'     => Carbon::now()->subDays($index),
                    'meta_title'       => $item['title'],
                    'meta_description' => $item['excerpt'],
                    'meta_keywords'    => 'layanan publik, PUPR, perizinan, konsultasi teknis, standar pelayanan',
                ]
            );
        }

        // for ($i = 1; $i <= 50; $i++) {
        //     $title = 'Artikel Lorem Ipsum ' . str_pad($i, 2, '0', STR_PAD_LEFT);
        //     $slug = Str::slug($title);

        //     BlogArticle::updateOrCreate(
        //         [
        //             'slug' => $slug,
        //         ],
        //         [
        //             'user_id'          => $user->id,
        //             'blog_category_id' => $uncategorizedCategory->id,
        //             'created_by'       => $user->id,
        //             'updated_by'       => $user->id,
        //             'title'            => $title,
        //             'excerpt'          => $this->generateExcerpt($i),
        //             'content'          => $this->generateContent($i),
        //             'thumbnail'        => null,
        //             'status'           => BlogArticle::STATUS_PUBLISHED,
        //             'is_featured'      => $i <= 5,
        //             'views_count'      => random_int(10, 500),
        //             'published_at'     => Carbon::now()->subDays($i + 3),
        //             'meta_title'       => $title,
        //             'meta_description' => $this->generateMetaDescription($i),
        //             'meta_keywords'    => 'blog, artikel, lorem ipsum, informasi, website',
        //         ]
        //     );
        // }
    }

    // private function generateExcerpt(int $index): string
    // {
    //     return 'Ringkasan artikel lorem ipsum ke-' . $index . ' yang digunakan sebagai contoh konten blog pada website.';
    // }

    // private function generateMetaDescription(int $index): string
    // {
    //     return 'Artikel lorem ipsum ke-' . $index . ' berisi contoh konten blog untuk kebutuhan pengujian tampilan, kategori, dan publikasi artikel.';
    // }

    // private function generateContent(int $index): string
    // {
    //     return '
    //         <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Artikel ini merupakan contoh konten blog ke-' . $index . ' yang digunakan untuk kebutuhan pengujian sistem.</p>

    //         <p>Praesent vitae magna at velit facilisis gravida. Integer non lectus nec lacus tincidunt malesuada. Donec at justo vitae augue faucibus posuere. Suspendisse potenti.</p>

    //         <h4>Pendahuluan</h4>

    //         <p>Curabitur porta, justo sed dapibus tristique, lorem sem fermentum nibh, vitae elementum justo neque sed sapien. Morbi sed erat vel nisi gravida luctus.</p>

    //         <h4>Pembahasan</h4>

    //         <p>Aliquam erat volutpat. Sed sit amet augue in ipsum feugiat commodo. Nulla facilisi. Aenean non magna id erat elementum condimentum. Fusce ultricies, lorem vitae tempor porta, justo purus sagittis urna, sed vestibulum justo enim sed erat.</p>

    //         <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Donec dignissim, risus non pretium gravida, sapien arcu tincidunt velit, sed varius lacus massa sed nibh.</p>

    //         <h4>Kesimpulan</h4>

    //         <p>Nam tincidunt, sem at luctus fermentum, libero purus suscipit erat, sed gravida magna justo sed erat. Artikel ini dapat digunakan sebagai data dummy untuk menguji tampilan daftar artikel, detail artikel, pencarian, dan pengelompokan berdasarkan kategori.</p>
    //     ';
    // }
}
