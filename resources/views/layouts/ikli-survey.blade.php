<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' | ' : '' }}{{ config('app.subname', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo-loteng-square.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-loteng-square.png') }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('landing/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet">

    <!-- BEGIN CUSTOM CSS-->
    @stack('styles')
    <!-- END CUSTOM CSS-->
</head>

<body @if (!request()->routeIs('ikli-survey.home')) class="bg-light" @endif>
    <div class="min-vh-100">
        {{-- Navbar Top --}}
        <nav class="navbar navbar-light fixed-top bg-white shadow-sm">
            <div class="container">
                <div class="{{ !request()->routeIs('ikli-survey.survey.create') ? 'ms-md-0 ms-auto' : '' }} me-auto">
                    @if (isset($brand))
                        {{ $brand }}
                    @endif
                </div>

                <div class="d-none d-md-inline">
                    @if (Auth::user())
                        <a href="{{ route('ikli-survey.dashboard.index') }}"
                            class="btn btn-outline-secondary rounded-pill fw-semibold text-uppercase">
                            <i class="fa-solid fa-desktop me-1"></i>
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="btn btn-outline-secondary rounded-pill fw-semibold text-uppercase">
                            <i class="fa-solid fa-right-to-bracket me-1"></i>
                            {{ __('Login') }}
                        </a>
                    @endif
                </div>
            </div>
        </nav>

        <main class="main-container">
            {{ $slot }}
        </main>

        @if (!isset($hideFooter))
            <footer class="border-top bg-secondary fixed-bottom">
                <div class="container py-3 text-center">
                    <div
                        class="d-flex align-items-center justify-content-center justify-content-md-between flex-wrap gap-2">
                        <small class="mb-0 text-white">
                            &copy; {{ date('Y') }} {{ config('app.subname', 'Laravel') }}.
                        </small>

                        <small class="mb-0 text-white">
                            Dibuat oleh <a href="https://nuansagiskonsultan.com/" target="_blank" class="text-white">CV.
                                Nuansa GIS
                                Konsultan</a>.
                        </small>
                    </div>
                </div>
            </footer>
        @endif
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert.min.js') }}"></script>

    <!-- Template Javascript -->
    {{-- <script src="{{ asset('landing/js/main.js') }}"></script> --}}
    @stack('scripts')

    <x-sweet-alert />
</body>

</html>
