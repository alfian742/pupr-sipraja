<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteTitle = config('app.subname', 'Laravel');

        $pageTitle = isset($title) && trim((string) $title) !== '' ? trim((string) $title) : '';

        $seoTitle = $pageTitle !== '' ? $pageTitle . ' | ' . $siteTitle : $siteTitle;

        $seoDescription = isset($metaDescription) ? trim((string) $metaDescription) : '';

        $seoKeywords = isset($metaKeywords) ? trim((string) $metaKeywords) : '';

        $seoImage = isset($metaImage) ? trim((string) $metaImage) : '';

        $seoCanonical = isset($canonicalUrl) ? trim((string) $canonicalUrl) : '';

        $seoType = isset($metaType) ? trim((string) $metaType) : '';

        $seoPublishedTime = isset($metaPublishedTime) ? trim((string) $metaPublishedTime) : '';

        $seoModifiedTime = isset($metaModifiedTime) ? trim((string) $metaModifiedTime) : '';
    @endphp

    <title>{{ $seoTitle }}</title>

    @if ($seoDescription !== '')
        <meta name="description" content="{{ $seoDescription }}">
    @endif

    @if ($seoKeywords !== '')
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endif

    @if ($seoCanonical !== '')
        <link rel="canonical" href="{{ $seoCanonical }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:site_name" content="{{ $siteTitle }}">
    <meta property="og:locale" content="id_ID">

    @if ($seoDescription !== '')
        <meta property="og:description" content="{{ $seoDescription }}">
    @endif

    @if ($seoType !== '')
        <meta property="og:type" content="{{ $seoType }}">
    @endif

    @if ($seoCanonical !== '')
        <meta property="og:url" content="{{ $seoCanonical }}">
    @endif

    @if ($seoImage !== '')
        <meta property="og:image" content="{{ $seoImage }}">
        <meta property="og:image:alt" content="{{ $pageTitle !== '' ? $pageTitle : $siteTitle }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">

    @if ($seoDescription !== '')
        <meta name="twitter:description" content="{{ $seoDescription }}">
    @endif

    @if ($seoImage !== '')
        <meta name="twitter:image" content="{{ $seoImage }}">
    @endif

    @if ($seoType === 'article')
        @if ($seoPublishedTime !== '')
            <meta property="article:published_time" content="{{ $seoPublishedTime }}">
        @endif

        @if ($seoModifiedTime !== '')
            <meta property="article:modified_time" content="{{ $seoModifiedTime }}">
        @endif
    @endif

    <!-- Favicon -->
    <link rel="apple-touch-icon" href="{{ asset('public/assets/images/logo-loteng-square.png') }}">
    <link rel="shortcut icon" href="{{ asset('public/assets/images/logo-loteng-square.png') }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('public/landing/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('public/landing/css/style.css') }}" rel="stylesheet">

    <!-- BEGIN CUSTOM CSS-->
    @stack('styles')
    <!-- END CUSTOM CSS-->
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner-loader" class="spinner-overlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Header Start -->
    <header class="border-bottom d-none d-lg-block bg-white">
        <div class="container px-4 py-2">
            <div class="row">
                <div class="col-8">
                    <div class="d-flex justify-content-start align-items-center gap-3">
                        <a class="text-decoration-none text-body" href="#!">
                            <i class="fa fa-phone me-2"></i> {{ $contact->phone_number ?? '' }}
                        </a>
                        <span class="text-body">|</span>
                        <a class="text-decoration-none text-body" href="mailto:{{ $contact->email ?? '' }}">
                            <i class="fa fa-envelope me-2"></i> {{ $contact->email ?? '' }}
                        </a>
                        <span class="text-body">|</span>
                        <span class="text-body"><i class="fa fa-clock me-2"></i>
                            {{ $contact->operational_time ?? '' }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        @if ($contact->whatsapp_number)
                            <!-- WhatsApp -->
                            <a class="text-body"
                                href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $contact->whatsapp_number)) }}"
                                target="_blank">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        @endif
                        @if ($contact->instagram_url)
                            <!-- Facebook -->
                            <a class="text-body" href="{{ $contact->facebook_url }}" target="_blank">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                        @endif
                        @if ($contact->instagram_url)
                            <!-- Instagram -->
                            <a class="text-body" href="{{ $contact->instagram_url }}" target="_blank">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        @endif
                        @if ($contact->twitter_url)
                            <!-- X/Twitter -->
                            <a class="text-body" href="{{ $contact->twitter_url }}" target="_blank">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                        @endif
                        @if ($contact->tiktok_url)
                            <!-- TikTok -->
                            <a class="text-body" href="{{ $contact->tiktok_url }}" target="_blank">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        @endif
                        @if ($contact->youtube_url)
                            <!-- YouTube -->
                            <a class="text-body" href="{{ $contact->youtube_url }}" target="_blank">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-light py-lg-0 sticky-top bg-white py-3 shadow-sm">
        <div class="container px-4">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img alt="Logo {{ config('app.subname', 'Laravel') }}"
                    src="{{ asset('public/assets/images/logo-pupr-loteng.png') }}" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <div class="navbar-nav text-lg-start ms-auto py-0 text-center">
                    <a href="{{ route('home') }}"
                        class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        Beranda
                    </a>

                    <div class="nav-item dropdown">
                        @php
                            $organizationProfileMenu = [
                                [
                                    'title' => 'Struktur Organisasi',
                                    'route' => 'organization-profiles.organization-structure',
                                ],
                                ['title' => 'Visi dan Misi', 'route' => 'organization-profiles.vision-and-mission'],
                                ['title' => 'Profil Personel', 'route' => 'organization-profiles.personnel-profiles'],
                            ];
                        @endphp
                        <a href="#"
                            class="nav-link dropdown-toggle {{ request()->routeIs('organization-profiles.*') ? 'active' : '' }}"
                            data-bs-toggle="dropdown">
                            Profil
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-end text-lg-start mt-lg-2 m-0 rounded border-0 text-center">
                            @foreach ($organizationProfileMenu as $menu)
                                <a href="{{ route($menu['route']) }}"
                                    class="dropdown-item {{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                                    {{ $menu['title'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                        @php
                            $otherInformationMenu = [
                                ['title' => 'Artikel', 'route' => 'blog.index'],
                                ['title' => 'FAQ', 'route' => 'other-informations.faqs'],
                                ['title' => 'Pusat Unduhan', 'route' => 'other-informations.download-center.index'],
                            ];
                        @endphp
                        <a href="#"
                            class="nav-link dropdown-toggle {{ request()->routeIs('blog.*') || request()->routeIs('other-informations.*') ? 'active' : '' }}"
                            data-bs-toggle="dropdown">
                            Informasi
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-end text-lg-start mt-lg-2 m-0 rounded border-0 text-center">
                            @foreach ($otherInformationMenu as $menu)
                                <a href="{{ route($menu['route']) }}"
                                    class="dropdown-item {{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                                    {{ $menu['title'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('contact') }}"
                        class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        Kontak
                    </a>
                    <a href="{{ Auth::user() ? route('dashboard.index') : route('login') }}"
                        class="nav-item nav-link">{{ Auth::user() ? 'Dashboard' : 'Masuk' }}</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    @if (!request()->routeIs('home'))
        <!-- Breadcrumb Start -->
        @include('layouts.partials.breadcrumb-landing')
        <!-- Breadcrumb End -->
    @endif

    <!-- Main Start -->
    <main @if (!request()->routeIs('home')) class="container px-4 py-5" @endif>
        {{ $slot }}
    </main>
    <!-- Main End -->

    <!-- Footer Start -->
    <footer class="bg-secondary text-light">
        <div class="container px-4 py-5">
            <div class="row g-4 g-lg-2">
                <div class="col-lg-4 col-md-6">
                    <h4 class="d-inline-block text-primary border-bottom border-primary border-3 mb-4 pb-1">
                        Hubungi Kami
                    </h4>
                    <p class="mb-4">Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Lombok Tengah</p>
                    <div class="row g-2">
                        <div class="col-1"><i class="fa fa-map-marker-alt text-primary me-3"></i></div>
                        <div class="col-11">{{ $contact->address ?? '' }}</div>
                        <div class="col-1"><i class="fa fa-envelope text-primary me-3"></i></div>
                        <div class="col-11">{{ $contact->email ?? '' }}</div>
                        <div class="col-1"><i class="fa fa-phone text-primary me-3"></i></div>
                        <div class="col-11">{{ $contact->phone_number ?? '' }}</div>
                        <div class="col-1"><i class="fa fa-clock text-primary me-3"></i></div>
                        <div class="col-11">{{ $contact->operational_time ?? '' }}</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="d-inline-block text-primary border-bottom border-primary border-3 mb-4 pb-1">
                        Menu
                    </h4>
                    <div class="footer-menu">
                        <!-- Beranda -->
                        <div class="mb-3">
                            <a class="text-light d-block" href="{{ route('home') }}">
                                <i class="fa fa-angle-right me-2"></i> Beranda
                            </a>
                        </div>

                        <!-- Profil Organisasi -->
                        <div class="mb-3">
                            <a class="text-light d-block" data-bs-toggle="collapse" href="#footerOrganizationProfile"
                                role="button">
                                <i class="fa fa-angle-right me-2"></i> Profil Organisasi
                            </a>
                            <div class="collapse ms-5 pt-2" id="footerOrganizationProfile">
                                @foreach ($organizationProfileMenu as $menu)
                                    <a href="{{ route($menu['route']) }}" class="text-light d-block small mb-2">
                                        {{ $menu['title'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Informasi -->
                        <div class="mb-3">
                            <a class="text-light d-block" data-bs-toggle="collapse" href="#footerAnotherInformation"
                                role="button">
                                <i class="fa fa-angle-right me-2"></i> Informasi
                            </a>
                            <div class="collapse ms-5 pt-2" id="footerAnotherInformation">
                                @foreach ($otherInformationMenu as $menu)
                                    <a href="{{ route($menu['route']) . '?page=' . urlencode($menu['title']) }}"
                                        class="text-light d-block small mb-2">
                                        {{ $menu['title'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Kontak -->
                        <div>
                            <a class="text-light d-block" href="{{ route('contact') }}">
                                <i class="fa fa-angle-right me-2"></i> Kontak
                            </a>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="d-inline-block text-primary border-bottom border-primary border-3 mb-4 pb-1">
                        Ikuti Kami
                    </h4>
                    <div class="d-flex flex-wrap gap-2">
                        @if ($contact->whatsapp_number)
                            <!-- WhatsApp -->
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2"
                                href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $contact->whatsapp_number)) }}"
                                target="_blank">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif

                        @if ($contact->facebook_url)
                            <!-- Facebook -->
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2"
                                href="{{ $contact->facebook_url }}" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif

                        @if ($contact->instagram_url)
                            <!-- Instagram -->
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2"
                                href="{{ $contact->instagram_url }}" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif

                        @if ($contact->twitter_url)
                            <!-- X/Twitter -->
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2"
                                href="{{ $contact->twitter_url }}" target="_blank">
                                <i class="fab fa-x-twitter"></i>
                            </a>
                        @endif

                        @if ($contact->tiktok_url)
                            <!-- TikTok -->
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2"
                                href="{{ $contact->tiktok_url }}" target="_blank">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        @endif

                        @if ($contact->youtube_url)
                            <!-- YouTube -->
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle"
                                href="{{ $contact->youtube_url }}" target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h4 class="d-inline-block text-primary border-bottom border-primary border-3 mb-4 pb-1">
                        Pengunjung
                    </h4>

                    <div class="row g-2">
                        <div class="col-1 col-lg-2"><i class="fa fa-users text-primary me-3"></i></div>
                        <div class="col-11 col-lg-10">Total: {{ $visitorData->total ?? 0 }}</div>
                        <div class="col-1 col-lg-2"><i class="fa fa-calendar-alt text-primary me-3"></i></div>
                        <div class="col-11 col-lg-10">Bulan Ini: {{ $visitorData->monthly ?? 0 }}</div>
                        <div class="col-1 col-lg-2"><i class="fa fa-sun text-primary me-3"></i></div>
                        <div class="col-11 col-lg-10">Hari Ini: {{ $visitorData->today ?? 0 }}</div>
                        <div class="col-1 col-lg-2"><i class="fa fa-user-clock text-primary me-3"></i></div>
                        <div class="col-11 col-lg-10">Online: {{ $visitorData->online ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-top border-light">
            <div class="container p-4 pb-3">
                <div
                    class="d-flex flex-md-nowrap justify-content-center justify-content-md-between align-items-center flex-wrap gap-2">
                    <p class="text-md-start mb-0 text-center">
                        &copy; {{ date('Y') }} {{ config('app.subname', 'Laravel') }}. Hak Cipta Dilindungi
                        Undang-Undang.
                    </p>
                    <p class="text-md-end mb-0 text-center">
                        Dibuat oleh <a class="text-primary" href="https://nuansagiskonsultan.com/"
                            target="_blank">CV.
                            Nuansa GIS Konsultan</a>.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer End -->

    @if ($contact->whatsapp_number)
        <!-- WhatsApp FAB -->
        <a class="btn btn-lg btn-success btn-lg-square rounded-circle wa-cta"
            href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $contact->whatsapp_number)) }}"
            target="_blank">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endif

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('public/app-assets/vendors/js/extensions/sweetalert.min.js') }}"></script>

    <!-- Template Javascript -->
    @stack('scripts')
    <script src="{{ asset('public/landing/js/main.js') }}"></script>
    <script src="{{ asset('public/landing/js/loader.js') }}"></script>

    <x-sweet-alert />
</body>

</html>
