<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
          <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $meta_title ?? config('app.name', 'Laravel') }}</title>
        <meta name="description" content="{{ $meta_desc ?? 'Default description' }}">
        <meta name="author" content="LMS">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="{{ $meta_title ?? config('app.name', 'Laravel') }}" />
        <meta property="og:description" content="{{ $meta_desc ?? 'Default description' }}" />
        <meta property="og:image" content="{{ $meta_image ?? '' }}" />
        
        <!-- Twitter Meta Tags -->
        <meta property="twitter:title" content="{{ $meta_title ?? config('app.name', 'Laravel') }}" />
        <meta property="twitter:description" content="{{ $meta_desc ?? 'Default description' }}" />
        <meta property="twitter:image" content="{{ $meta_image ?? '' }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
          <link href="https://fonts.googleapis.com/css?family=Montserrat:500,600,700&amp;display=swap" rel="stylesheet">
           <link rel="shortcut icon" href="{{ url('assets/images/logo/favicon.png?v=' .env('CACHE_VERSION')) }}">
    <!-- Bootstrap -->
  <link rel="stylesheet" href="{{ url('landing/css/main.css?v='.env('CACHE_VERSION')) }}" />
  <script src="{{ url('landing/js/uikit.js?v=' .env('CACHE_VERSION')) }}"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="">

        <!--==================== Sidebar Overlay End ====================-->
<div class="side-overlay"></div>
<!--==================== Sidebar Overlay End ====================-->
        
     
            <div >
                {{ $slot }}
            </div>

        <!-- Jquery js -->
    <script src="{{ url('assets/js/jquery-3.7.1.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="{{ url('assets/js/boostrap.bundle.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- Phosphor Js -->
    <script src="{{ url('assets/js/phosphor-icon.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- file upload -->
    <script src="{{ url('assets/js/file-upload.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- file upload -->
    <script src="{{ url('assets/js/plyr.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- dataTables -->
    <script src="{{ url('../../cdn.datatables.net/2.0.8/js/dataTables.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- full calendar -->
    <script src="{{ url('assets/js/full-calendar.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- jQuery UI -->
    <script src="{{ url('assets/js/jquery-ui.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- jQuery UI -->
    <script src="{{ url('assets/js/editor-quill.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- apex charts -->
    <script src="{{ url('assets/js/apexcharts.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- jvectormap Js -->
    <script src="{{ url('assets/js/jquery-jvectormap-2.0.5.min.js?v=' .env("CACHE_VERSION")) }}"></script>
    <!-- jvectormap world Js -->
    <script src="{{ url('assets/js/jquery-jvectormap-world-mill-en.js?v=' .env("CACHE_VERSION")) }}"></script>
    
    <!-- main js -->
    <script src="{{ url('assets/js/main.js?v=' .env("CACHE_VERSION")) }}"></script>
    </body>
</html>