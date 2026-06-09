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
                                            ? asset('public/' . $carousel->image_path)
                                            : asset('public/assets/images/placeholder.svg');

                                        $altText = trim(
                                            $carousel->title . ' ' . strip_tags($carousel->description ?? ''),
                                        );
                                    @endphp

                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }} h-100">
                                        <img src="{{ $image }}" class="d-block w-100 h-100"
                                            alt="{{ $altText ?: 'Hero carousel' }}">
                                    </div>
                                @empty
                                    <div class="carousel-item active h-100">
                                        <img src="{{ asset('public/landing/img/hero.jpg') }}"
                                            class="d-block w-100 h-100" alt="Foto DPUPR Kabupaten Lombok Tengah">
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
    <section class="container px-4 py-5" id="about-section">
        <div class="row gx-5">
            <div class="col-lg-5 d-none d-lg-inline" style="min-height: 280px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100 rounded" style="object-fit: cover;"
                        alt="Illustrations by Storyset" src="{{ asset('public/landing/img/about.svg') }}"
                        loading="eager">
                </div>
            </div>
            <div class="col-lg-7">
                <h1 class="display-4 mb-4">Tentang Kami</h1>
                <div>
                    {!! $organizationProfile->organization_about ?? '' !!}
                </div>
            </div>
        </div>
    </section>
    <!-- About End -->

    <!-- Department Start -->
    <section class="container px-4 py-5" id="department-section">
        <div class="row justify-content-center g-5">
            @forelse ($departments as $item)
                <div class="col-lg-4 col-md-6">
                    <div
                        class="service-item bg-light d-flex flex-column align-items-center justify-content-center zoom-hover rounded-2 overflow-hidden rounded text-center shadow-sm">
                        @if (!empty($item->logo))
                            <img src="{{ asset('public/' . $item->logo) }}" class="d-block mx-auto mb-4 rounded"
                                style="height: 100px; width:100px; object-fit: cover;"
                                alt="{{ $item->department_name }}">
                        @else
                            @php
                                $icon = match ($item->department_name) {
                                    'Bina Marga' => 'fa-road',
                                    'Cipta Karya' => 'fa-building',
                                    'Sumber Daya Air (SDA)' => 'fa-water',
                                    'Penataan Ruang' => 'fa-map',
                                    'Sekretariat' => 'fa-users-cog',
                                    default => 'fa-times', // ikon default kalau tidak cocok
                                };
                            @endphp

                            <div class="service-icon mb-4">
                                <i class="fa fa-2x {{ $icon }} text-white"></i>
                            </div>
                        @endif

                        <h4 class="mb-3">{{ $item->department_name }}</h4>
                        <p class="m-0">{{ $item->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <i class="fa fa-times fa-4x text-muted mb-4 opacity-50"></i>
                        <h4 class="text-muted fw-bold mb-2">Bidang Belum Tersedia</h4>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
    <!-- Department End -->

    <!-- Vision Mision Start -->
    <section class="bg-light my-5 py-5" id="vision-mission-section">
        <div class="container px-4 py-5">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center">
                    <h2 class="display-4 mb-4">Visi dan Misi</h2>

                    <p class="text-dark">
                        {!! $organizationProfile->organization_summary ?? '' !!}
                    </p>
                </div>
            </div>

            <div class="row gx-5">
                <!-- Visi -->
                <div class="col-lg-6 d-none d-lg-inline" style="min-height: 280px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100 rounded" style="object-fit: cover;"
                            alt="Illustrations by Storyset" src="{{ asset('public/landing/img/vision-mision.svg') }}"
                            loading="eager">
                    </div>
                </div>

                <!-- Misi -->
                <div class="col-lg-6">
                    <div class="bg-secondary h-100 rounded p-4 text-white">
                        <div class="mb-5">
                            <h3 class="mb-3 text-white">Visi</h3>

                            {!! $organizationProfile->organization_vision ?? '' !!}
                        </div>

                        <div>
                            <h3 class="mb-3 text-white">Misi</h3>

                            {!! $organizationProfile->organization_mission ?? '' !!}
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
                <h2 class="display-4 mb-4">Artikel Terbaru</h2>

                <p class="text-dark">
                    Temukan informasi, publikasi, dan artikel terbaru dari
                    {{ config('app.subname', 'Laravel') }} sebagai media informasi
                    dan edukasi bagi masyarakat.
                </p>
            </div>
        </div>

        @if (isset($latestArticles) && $latestArticles->count())
            <div class="owl-carousel public-service-carousel position-relative" style="padding: 0 45px 45px 45px;">
                @foreach ($latestArticles as $item)
                    <div class="rounded-2 h-100 overflow-hidden bg-white shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $item->thumbnail ? asset('public/' . $item->thumbnail) : asset('public/assets/images/placeholder.svg') }}"
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
                                Selengkapnya
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
    <section class="bg-light mt-5 py-5" id="vision-mission-section">
        <div class="container px-4 py-5">
            <div class="row justify-content-center">
                <div class="col-lg-7 mb-5 text-center">
                    <h2 class="display-4 mb-4">FAQ</h2>

                    <p class="text-dark">
                        Temukan jawaban atas pertanyaan yang sering diajukan masyarakat terkait layanan
                        publik dan berbagai informasi lainnya di {{ config('app.subname', 'Laravel') }}.
                    </p>
                </div>
                <div class="col-lg-7 mb-5">
                    <div class="accordion" id="faqAccordion">
                        @forelse ($faqs as $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeading{{ $loop->iteration }}">
                                    <button class="accordion-button collapsed bg-secondary text-white" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq{{ $loop->iteration }}"
                                        aria-expanded="false" aria-controls="faq{{ $loop->iteration }}">
                                        {{ $faq->faq_question }}
                                    </button>
                                </h2>
                                <div id="faq{{ $loop->iteration }}" class="accordion-collapse collapse"
                                    aria-labelledby="faqHeading{{ $loop->iteration }}"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-dark">
                                        {!! $faq->faq_answer !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-5 text-center">
                                <i class="fa fa-users fa-4x text-muted mb-4 opacity-50"></i>
                                <h4 class="text-muted fw-bold mb-2">FAQ Belum Tersedia</h4>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if (!empty($faqs))
                    <div class="col-lg-7">
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('other-informations.faqs') }}"
                                class="btn btn-lg btn-secondary rounded-pill">Selengkapnya <i
                                    class="fa fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- FAQ End -->

    @push('styles')
        <!-- Libraries Stylesheet -->
        <link href="{{ asset('public/landing/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('public/landing/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}"
            rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="{{ asset('public/landing/lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('public/landing/lib/waypoints/waypoints.min.js') }}"></script>
        <script src="{{ asset('public/landing/lib/owlcarousel/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('public/landing/lib/tempusdominus/js/moment.min.js') }}"></script>
        <script src="{{ asset('public/landing/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
        <script src="{{ asset('public/landing/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

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
