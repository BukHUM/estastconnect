<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PropFinder - ค้นหาโครงการอสังหาริมทรัพย์')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'รวบรวมบ้านและคอนโดทำเลดี พร้อมข้อเสนอสุดพิเศษที่คุณไม่ควรพลาด')">
    <meta name="keywords" content="@yield('keywords', 'อสังหาริมทรัพย์, บ้าน, คอนโด, ที่ดิน, โครงการใหม่')">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('og_title', 'PropFinder - ค้นหาโครงการอสังหาริมทรัพย์')">
    <meta property="og:description" content="@yield('og_description', 'รวบรวมบ้านและคอนโดทำเลดี พร้อมข้อเสนอสุดพิเศษ')">
    <meta property="og:image" content="@yield('og_image', asset('favicon.ico'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Analytics (ถ้ามี) -->
    @if(config('services.google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('services.google_analytics_id') }}');
    </script>
    @endif
</head>
<body class="bg-slate-50 font-sans text-slate-900">
    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main class="min-h-screen pb-8">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    @stack('scripts')
</body>
</html>

