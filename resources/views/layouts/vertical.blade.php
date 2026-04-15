<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">



<head>
    @include('layouts.partials/title-meta', ['title' => $title])
    @yield('css')
    <!-- Vendor css (Require in all Page) -->
    <link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons css (Require in all Page) -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css (Require in all Page) -->
    <!-- <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" /> -->

    @if(app()->getLocale() == 'ar')
        {{-- Techzaa RTL bundle (added per request) --}}
        <link rel="stylesheet" href="{{ asset('css/app-rtl.min.css') }}">
    @else
        {{-- Techzaa LTR bundle (added per request) --}}
        <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
    @endif
    <script src="{{ asset('js/config.js') }}"></script>
    <!-- @include('layouts.partials/head-css') -->
</head>

<body>

    <div class="wrapper">

        @include("layouts.partials/topbar", ['title' => $title])
        @include('layouts.partials/main-nav')

        <div class="page-content">

            <div class="container-fluid">
                @yield('content')
            </div>

            @include("layouts.partials/footer")

        </div>

    </div>

    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ asset('js/vendor.js') }}"></script>
    @include("layouts.partials/footer-scripts")
    <script>
        // Prevent Dropzone from auto-discovering so that page-specific Vite scripts can initialize it
        if (window.Dropzone) {
            Dropzone.autoDiscover = false;
        }
    </script>


    <!-- App Javascript (Require in all Page) -->
    <script src="{{ asset('js/app.min.js') }}"></script>

    <!-- Vector Map Js -->
    <script src="{{ asset('js/vendor/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('js/vendor/jsvectormap/maps/world.js') }}"></script>

    <!-- Dashboard Js -->
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>
    <!-- @vite(['resources/js/app.js', 'resources/js/layout.js']) -->

</body>

</html>