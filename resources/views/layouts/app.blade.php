<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' | ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <!-- FAVICON -->
    <link rel="apple-touch-icon" href="{{ asset('public/assets/images/logo-sipraja.png') }}">
    <link rel="shortcut icon" href="{{ asset('public/assets/images/logo-sipraja.png') }}">

    <!-- GOOGLE WEB FONTS -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700"
        rel="stylesheet">

    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/vendors.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/vendors/css/extensions/sweetalert.css') }}">
    <!-- END VENDOR CSS-->

    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/app.css') }}">
    <!-- END ROBUST CSS-->

    <!-- BEGIN PAGE CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/menu/menu-types/vertical-menu.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/colors/palette-climacon.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/colors/palette-callout.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/pages/users.css') }}">
    @stack('styles')
    <!-- END PAGE CSS-->

    <!-- BEGIN CUSTOM CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/style.css') }}">
    <!-- END CUSTOM CSS-->
</head>

<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click"
    data-menu="vertical-menu" data-col="2-columns">

    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="app-content content">
        <div class="content-wrapper my-2">
            @if (!request()->routeIs('dashboard.index'))
                {{-- Breadcrumb --}}
                @include('layouts.partials.breadcrumb')
            @endif

            {{-- Content --}}
            {{ $slot }}
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    {{-- Scroll to Top Button --}}
    <button type="button" id="scrollToTopPage" class="btn btn-secondary" title="Ke Atas">
        <i class="fa fa-arrow-up"></i>
    </button>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('public/app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN VENDOR JS-->

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{ asset('public/app-assets/vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/ui/headroom.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/extensions/sweetalert.min.js') }}"></script>
    @stack('scripts')
    <!-- END PAGE VENDOR JS-->

    <!-- BEGIN ROBUST JS-->
    <script src="{{ asset('public/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('public/app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('public/app-assets/js/scripts/customizer.min.js') }}"></script>
    <!-- END ROBUST JS-->

    <!-- BEGIN CUSTOM JS-->
    <script src="{{ asset('public/assets/js/scripts.js') }}"></script>
    <!-- END CUSTOM JS-->
</body>

</html>
