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
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/forms/icheck/icheck.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/forms/icheck/custom.css') }}">
    <!-- END VENDOR CSS-->

    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/app.css') }}">
    <!-- END ROBUST CSS-->

    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/menu/menu-types/vertical-content-menu.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/pages/login-register.css') }}">
    <!-- END Page Level CSS-->

    <!-- BEGIN CUSTOM CSS-->
    <style>
        .card-auth {
            overflow: hidden !important;
            border-radius: 10px !important;
        }
    </style>
    @stack('styles')
    <!-- END CUSTOM CSS-->
</head>

<body class="vertical-layout vertical-content-menu 1-column bg-light menu-expanded blank-page blank-page"
    data-open="click" data-menu="vertical-content-menu" data-col="1-column">
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                {{ $slot }}
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('public/app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN VENDOR JS-->

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{ asset('public/app-assets/vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/charts/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/ui/headroom.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/forms/icheck/icheck.min.js') }}"></script>
    <!-- END PAGE VENDOR JS-->

    <!-- BEGIN ROBUST JS-->
    <script src="{{ asset('public/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('public/app-assets/js/core/app.js') }}"></script>
    <!-- END ROBUST JS-->

    <!-- BEGIN PAGE LEVEL JS-->
    <script src="{{ asset('public/app-assets/js/scripts/forms/form-login-register.js') }}"></script>
    <!-- END PAGE LEVEL JS-->

    <!-- BEGIN CUSTOM JS-->
    @stack('scripts')
    <!-- END CUSTOM JS-->
</body>

</html>
