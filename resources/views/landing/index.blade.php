<x-guest-layout>
    @php $pageTitle = 'Beranda'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <!-- Hero Start -->
    <section class="bg-secondary hero-header hero-carousel-layout mb-5 py-5" id="hero-section">
        <div class="container px-4 py-5">
            <div class="row align-items-center justify-content-between g-5">
                <!-- Hero Left Content -->
                <div class="col-xl-7 col-lg-6 text-center text-lg-start">
                    <div class="hero-content-wrap">
                        <h5 class="hero-eyebrow d-inline-block text-primary border-bottom border-4 mb-4 pb-2">
                            Selamat Datang di Situs Resmi
                        </h5>

                        <h1 class="hero-title display-3 text-uppercase mb-4 text-white">
                            Dinas Pekerjaan Umum dan Penataan Ruang
                        </h1>

                        <h4 class="hero-subtitle text-uppercase mb-4 text-white">
                            Kabupaten Lombok Tengah
                        </h4>

                        <p class="hero-description mb-5">
                            Mendukung pembangunan infrastruktur yang berkualitas, berkelanjutan,
                            dan memberikan kemudahan akses informasi layanan publik bagi masyarakat.
                        </p>

                        <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                            <a href="#about-section" id="ctaBtn"
                                class="btn btn-primary rounded-pill rounded-pill py-md-3 px-md-5 px-4 py-2">
                                Selengkapnya
                                <i class="fa fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Hero Right Photo Carousel -->
                <div class="col-xl-5 col-lg-6">
                    <div class="hero-photo-card mx-auto ms-lg-auto">
                        <div id="heroPhotoCarousel" class="carousel slide h-100" data-bs-ride="carousel">
                            @php
                                $carousels = $heroCarousels ?? collect();
                            @endphp

                            @if ($carousels->count() > 1)
                                <div class="carousel-indicators hero-carousel-indicators">
                                    @foreach ($carousels as $index => $carousel)
                                        <button type="button" data-bs-target="#heroPhotoCarousel"
                                            data-bs-slide-to="{{ $index }}"
                                            class="{{ $index === 0 ? 'active' : '' }}"
                                            @if ($index === 0) aria-current="true" @endif
                                            aria-label="Slide {{ $index + 1 }}">
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            <div class="carousel-inner h-100 rounded-4 overflow-hidden">
                                @forelse ($carousels as $index => $carousel)
                                    @php
                                        $image = $carousel->image_path
                                            ? asset($carousel->image_path)
                                            : asset('assets/images/placeholder.svg');

                                        $altText = trim(
                                            $carousel->title . ' ' . strip_tags($carousel->description ?? ''),
                                        );
                                    @endphp

                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }} h-100">
                                        <img src="{{ $image }}" class="d-block w-100 h-100"
                                            alt="{{ $altText ?: 'Hero carousel' }}" loading="eager">
                                    </div>
                                @empty
                                    <div class="carousel-item active h-100">
                                        <img src="{{ asset('landing/img/hero.jpg') }}" class="d-block w-100 h-100"
                                            alt="Foto DPUPR Kabupaten Lombok Tengah">
                                    </div>
                                @endforelse
                            </div>

                            @if ($carousels->count() > 1)
                                <button class="carousel-control-prev hero-carousel-control" type="button"
                                    data-bs-target="#heroPhotoCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Sebelumnya</span>
                                </button>

                                <button class="carousel-control-next hero-carousel-control" type="button"
                                    data-bs-target="#heroPhotoCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Berikutnya</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero End -->

    <!-- About Start -->
    <section class="about-modern-section" id="about-section">
        <div class="container px-4">
            <div class="row align-items-center g-0 about-modern-wrapper">
                <div class="col-lg-5">
                    <div class="about-modern-visual">
                        <div class="about-modern-visual-bg"></div>

                        <img src="{{ asset('landing/img/about.svg') }}" class="about-modern-image"
                            alt="Illustrations by Storyset" loading="lazy">

                        <span class="about-modern-dot about-modern-dot-one"></span>
                        <span class="about-modern-dot about-modern-dot-two"></span>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="about-modern-content">
                        <div class="about-modern-heading">
                            <span class="about-modern-heading-line"></span>
                            <h2 class="display-5 mb-0">Tentang Kami</h2>
                        </div>

                        <div class="about-modern-description">
                            {!! $organizationProfile->organization_about ?? '' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About End -->

    <!-- Department Start -->
    <section class="department-section py-5" id="department-section">
        <div class="container px-4">
            <div class="department-header text-center mx-auto mb-5">
                <span class="department-subtitle">Unit Kerja</span>
                <h2 class="display-6 mb-3">{{ config('app.subname', 'Laravel') }}</h2>
                <span class="department-title-line"></span>
            </div>

            <div class="row justify-content-center g-4">
                @forelse ($departments as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="department-card h-100">
                            <div class="department-card-inner">
                                <div class="department-icon-wrapper">
                                    @if (!empty($item->logo))
                                        <img src="{{ asset($item->logo) }}" class="department-logo"
                                            alt="{{ $item->department_name }}" loadig="lazy">
                                    @else
                                        @php
                                            $icon = match ($item->department_name) {
                                                'Bina Marga' => 'fa-road',
                                                'Cipta Karya' => 'fa-building',
                                                'Sumber Daya Air (SDA)' => 'fa-water',
                                                'Penataan Ruang' => 'fa-map',
                                                'Sekretariat' => 'fa-users-cog',
                                                default => 'fa-times',
                                            };
                                        @endphp

                                        <i class="fa fa-2x {{ $icon }} text-white"></i>
                                    @endif
                                </div>

                                <h4 class="department-name mb-3">{{ $item->department_name }}</h4>

                                <p class="department-description mb-0">
                                    {{ $item->description }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-5 col-md-8">
                        <div class="department-empty text-center">
                            <div class="department-empty-icon mx-auto mb-4">
                                <i class="fa fa-times fa-2x text-white"></i>
                            </div>

                            <h4 class="text-muted fw-bold mb-0">Bidang Belum Tersedia</h4>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Department End -->

    <!-- Vision Mision Start -->
    <section class="vision-mission-section" id="vision-mission-section">
        <div class="container px-4">
            <div class="vision-mission-header text-center mx-auto">
                <h2 class="display-5 vision-mission-title mb-3">Visi dan Misi</h2>
                <span class="vision-mission-title-line"></span>

                <div class="vision-mission-summary mt-4">
                    {!! $organizationProfile->organization_summary ?? '' !!}
                </div>
            </div>

            <div class="row align-items-center g-5 mt-4">
                <div class="col-lg-5">
                    <div class="vision-mission-visual">
                        <div class="vision-mission-image-card">
                            <img src="{{ asset('landing/img/vision-mision.svg') }}" class="vision-mission-image"
                                alt="Illustrations by Storyset" loading="lazy">
                        </div>

                        <span class="vision-mission-shape vision-mission-shape-one"></span>
                        <span class="vision-mission-shape vision-mission-shape-two"></span>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="vision-mission-content">
                        <div class="vision-mission-item vision-item">
                            <div class="vision-mission-icon">
                                <i class="fa fa-eye text-white"></i>
                            </div>

                            <div class="vision-mission-text-area">
                                <h3 class="vision-mission-heading mb-3">Visi</h3>

                                <div class="vision-mission-text">
                                    {!! $organizationProfile->organization_vision ?? '' !!}
                                </div>
                            </div>
                        </div>

                        <div class="vision-mission-item mission-item">
                            <div class="vision-mission-icon">
                                <i class="fa fa-bullseye text-white"></i>
                            </div>

                            <div class="vision-mission-text-area">
                                <h3 class="vision-mission-heading mb-3">Misi</h3>

                                <div class="vision-mission-text">
                                    {!! $organizationProfile->organization_mission ?? '' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Vision Mision End -->

    <!-- Blog Start -->
    <section class="container px-4 py-5" id="blog-section">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center">
                <h2 class="display-5 mb-3">Artikel Terbaru</h2>
                <span class="article-title-line"></span>

                <p class="text-dark mt-4">
                    Temukan informasi, publikasi, dan artikel terbaru dari
                    {{ config('app.subname', 'Laravel') }} sebagai media informasi
                    dan edukasi bagi masyarakat.
                </p>
            </div>
        </div>

        @if (isset($latestArticles) && $latestArticles->count())
            <div class="owl-carousel article-carousel position-relative" style="padding: 0 45px 45px 45px;">
                @foreach ($latestArticles as $item)
                    <div class="rounded-2 h-100 overflow-hidden bg-white shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $item->thumbnail ? asset($item->thumbnail) : asset('assets/images/placeholder.svg') }}"
                                class="w-100" style="aspect-ratio: 4/3; object-fit: cover;"
                                alt="{{ $item->title ?? 'Artikel' }}">
                        </div>

                        <div class="p-4">
                            <div class="mb-3">
                                <span class="badge bg-secondary">
                                    {{ $item->category->name ?? 'Tak Berkategori' }}
                                </span>

                                @if ($item->is_featured)
                                    <span class="badge bg-warning text-dark">
                                        Unggulan
                                    </span>
                                @endif
                            </div>

                            <h4 class="fw-bold mb-3">
                                <a href="{{ route('blog.show', $item->slug) }}"
                                    class="text-dark text-decoration-none">
                                    {{ \Illuminate\Support\Str::limit($item->title, 65) }}
                                </a>
                            </h4>

                            <div class="d-flex flex-wrap text-secondary small mb-3" style="gap: .75rem">
                                <span>
                                    <i class="fa fa-calendar-o me-1"></i>
                                    {{ $item->published_at ? $item->published_at->translatedFormat('d F Y') : '-' }}
                                </span>
                                <span>
                                    <i class="fa fa-user-o me-1"></i>
                                    {{ $item->author->name ?? 'Admin' }}
                                </span>
                                <span>
                                    <i class="fa fa-eye me-1"></i>
                                    {{ number_format($item->views_count ?? 0, 0, ',', '.') }}
                                </span>
                            </div>

                            <p class="text-muted mb-3">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->excerpt ?: $item->content), 120) }}
                            </p>

                            <a href="{{ route('blog.show', $item->slug) }}"
                                class="btn btn-primary rounded-pill px-4">
                                Baca Artikel
                                <i class="fa fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                <a href="{{ route('blog.index') }}" class="btn btn-lg btn-secondary rounded-pill">Lihat Semua Artikel
                    <i class="fa fa-arrow-right ms-2"></i>
                </a>
            </div>
        @else
            <div class="py-5 text-center">
                <i class="fa fa-newspaper-o fa-4x text-muted mb-4 opacity-50"></i>
                <h4 class="text-muted fw-bold mb-2">Artikel Belum Tersedia</h4>
                <p class="text-muted mb-0">
                    Belum ada artikel yang dipublikasikan.
                </p>
            </div>
        @endif
    </section>
    <!-- Blog End -->

    <!-- FAQ Start -->
    <section class="faq-section" id="faq-section">
        <div class="container px-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="faq-header text-center mx-auto mb-5">
                        <h2 class="display-5 faq-title mb-3">FAQ</h2>
                        <span class="faq-title-line"></span>

                        <p class="faq-description mt-4 mb-0">
                            Temukan jawaban atas pertanyaan yang sering diajukan masyarakat terkait layanan
                            publik dan berbagai informasi lainnya di {{ config('app.subname', 'Laravel') }}.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    @if ($faqs->count() > 0)
                        <div class="faq-accordion-wrapper">
                            <div class="accordion faq-accordion" id="faqAccordion">
                                @foreach ($faqs as $faq)
                                    @php
                                        $faqNumber = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                                        $headingId = 'faqHeading' . $loop->iteration;
                                        $collapseId = 'faqCollapse' . $loop->iteration;
                                    @endphp

                                    <div class="accordion-item faq-item">
                                        <h2 class="accordion-header" id="{{ $headingId }}">
                                            <button class="accordion-button faq-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="false" aria-controls="{{ $collapseId }}">
                                                <span class="faq-number">
                                                    {{ $faqNumber }}
                                                </span>

                                                <span class="faq-question">
                                                    {{ $faq->faq_question }}
                                                </span>
                                            </button>
                                        </h2>

                                        <div id="{{ $collapseId }}" class="accordion-collapse collapse"
                                            aria-labelledby="{{ $headingId }}" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body faq-answer pt-2">
                                                {!! $faq->faq_answer !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="faq-empty text-center">
                            <div class="faq-empty-icon mx-auto mb-4">
                                <i class="fa fa-question text-white"></i>
                            </div>

                            <h4 class="text-muted fw-bold mb-0">FAQ Belum Tersedia</h4>
                        </div>
                    @endif
                </div>
            </div>

            @if ($faqs->count() > 0)
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-7">
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('other-informations.faqs') }}"
                                class="btn btn-lg btn-secondary rounded-pill">
                                Lihat Semua FAQ
                                <i class="fa fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- FAQ End -->

    <!-- Public Informartion Portal Start -->
    <section class="portal-logo-loop-section py-5" id="public-information-portal-section">
        <div class="container px-4 mb-4">
            <div class="portal-logo-loop-header text-center mx-auto mb-5">
                <h3 class="mb-1">Portal Informasi Publik</h3>
                <h2 class="display-5 mb-3">Kabupaten Lombok Tengah</h2>
                <span class="portal-logo-loop-title-line"></span>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    @php
                        $portalItems = $publicInformationPortals;
                    @endphp

                    @if ($portalItems->isNotEmpty())
                        <div class="portal-logo-loop-wrapper">
                            <div class="portal-logo-loop-viewport">
                                <div class="portal-logo-loop-track">
                                    @foreach (range(1, 2) as $loopGroup)
                                        @foreach ($portalItems as $item)
                                            @php
                                                $logoPath = $item->logo
                                                    ? asset($item->logo)
                                                    : asset('assets/images/logo-loteng-square.png');

                                                $hasWebsite = !empty($item->website_url);
                                                $isDuplicate = $loopGroup === 2;
                                            @endphp

                                            <div class="portal-logo-loop-item"
                                                @if ($isDuplicate) aria-hidden="true" @endif>
                                                @if ($hasWebsite && !$isDuplicate)
                                                    <a href="{{ $item->website_url }}" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="portal-logo-loop-card portal-logo-loop-card-link"
                                                        aria-label="Kunjungi website {{ $item->portal_name }}">
                                                        <div class="portal-logo-loop-image-box">
                                                            <img src="{{ $logoPath }}"
                                                                class="portal-logo-loop-image" loading="lazy"
                                                                alt="{{ $item->portal_name }}">
                                                        </div>

                                                        <h5 class="portal-logo-loop-name">
                                                            {{ $item->portal_name }}
                                                        </h5>
                                                    </a>
                                                @else
                                                    <div class="portal-logo-loop-card">
                                                        <div class="portal-logo-loop-image-box">
                                                            <img src="{{ $logoPath }}"
                                                                class="portal-logo-loop-image" loading="lazy"
                                                                alt="{{ $isDuplicate ? '' : $item->portal_name }}">
                                                        </div>

                                                        <h5 class="portal-logo-loop-name">
                                                            {{ $item->portal_name }}
                                                        </h5>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="portal-logo-loop-empty text-center">
                            <h4 class="text-muted fw-bold mb-0">Portal Informasi Publik Belum Tersedia</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- Public Informartion Portal End -->

    @push('styles')
        <!-- Libraries Stylesheet -->
        <link href="{{ asset('landing/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('landing/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="{{ asset('landing/lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('landing/lib/waypoints/waypoints.min.js') }}"></script>
        <script src="{{ asset('landing/lib/owlcarousel/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('landing/lib/tempusdominus/js/moment.min.js') }}"></script>
        <script src="{{ asset('landing/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
        <script src="{{ asset('landing/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

        <script>
            document.getElementById('ctaBtn').addEventListener('click', function(e) {
                e.preventDefault();

                const targetID = this.getAttribute('href');
                const target = document.querySelector(targetID);

                const offset = 96;
                const targetPos = target.getBoundingClientRect().top + window.pageYOffset - offset;

                smoothScrollTo(targetPos, 800); // durasi 800ms
            });

            function smoothScrollTo(targetY, duration) {
                const startY = window.pageYOffset;
                const distance = targetY - startY;
                const startTime = performance.now();

                // easing cubicInOut untuk scroll lebih halus
                function ease(t) {
                    return t < 0.5 ?
                        4 * t * t * t :
                        1 - Math.pow(-2 * t + 2, 3) / 2;
                }

                function animation(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = ease(progress);

                    window.scrollTo(0, startY + distance * eased);

                    if (elapsed < duration) {
                        requestAnimationFrame(animation);
                    }
                }

                requestAnimationFrame(animation);
            }
        </script>
    @endpush
</x-guest-layout>
