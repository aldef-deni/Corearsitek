<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#060607">
    <title>@yield('title', $contents['site_name'] ?? 'CoreArsitek')</title>

    @php
        $logo = $contents['logo_image'] ?? 'images/logo.png';
        $ogImage = $contents['og_image'] ?? 'images/og-image.jpg';
    @endphp

    {{-- SEO --}}
    <meta name="description" content="@yield('meta_description', $contents['meta_description'] ?? '')">
    <meta name="keywords" content="{{ $contents['meta_keywords'] ?? '' }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="{{ $contents['site_name'] ?? 'CoreArsitek' }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', $contents['site_name'] ?? 'CoreArsitek')">
    <meta property="og:description" content="@yield('meta_description', $contents['meta_description'] ?? '')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($ogImage) }}">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $contents['site_name'] ?? 'CoreArsitek')">
    <meta name="twitter:description" content="@yield('meta_description', $contents['meta_description'] ?? '')">
    <meta name="twitter:image" content="{{ asset($ogImage) }}">

    @yield('structured_data')

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: 1 }}">
</head>
<body>

<header class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ $contents['site_name'] ?? 'CoreArsitek' }}">
            <img src="{{ asset($logo) }}" alt="{{ $contents['site_name'] ?? 'CoreArsitek' }}" class="brand-logo">
        </a>

        <nav class="nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('portfolio') }}" class="{{ request()->routeIs('portfolio') ? 'active' : '' }}">Portofolio</a>
            <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">Layanan</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang CoreArsitek</a>
            <a href="{{ route('home') }}#kontak">Kontak</a>
        </nav>

        <div class="nav-actions">
            <a href="{{ route('admin.login') }}" title="Masuk Admin"><i class="fa-regular fa-user"></i></a>
            <span class="lang-btn">EN</span>
            <button class="nav-toggle" id="navToggle" aria-label="Buka menu"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="footer">
    <div class="container footer-grid">
        <div>
            <div class="brand footer-brand">
                <img src="{{ asset($logo) }}" alt="{{ $contents['site_name'] ?? 'CoreArsitek' }}" class="brand-logo">
            </div>
            <p>{{ $contents['footer_text'] ?? '' }}</p>
        </div>
        <div>
            <h4>Menu</h4>
            <ul>
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('services') }}">Layanan</a></li>
                <li><a href="{{ route('portfolio') }}">Portofolio</a></li>
                <li><a href="{{ route('about') }}">Tentang CoreArsitek</a></li>
                <li><a href="{{ route('home') }}#kontak">Kontak</a></li>
            </ul>
        </div>
        <div>
            <h4>Kontak</h4>
            <ul class="footer-contact">
                <li><i class="fa-solid fa-location-dot"></i> {{ $contents['contact_address'] ?? '' }}</li>
                <li><i class="fa-solid fa-phone"></i> {{ $contents['contact_phone'] ?? '' }}</li>
                <li><i class="fa-solid fa-envelope"></i> {{ $contents['contact_email'] ?? '' }}</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">{{ $contents['footer_copyright'] ?? '© ' . date('Y') . ' CoreArsitek. All rights reserved.' }}</div>
    </div>
</footer>

<div class="floating-actions">
    <a class="fab fab-wa" href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" rel="noopener" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
    <a class="fab fab-top" href="#" id="backToTop" title="Kembali ke atas"><i class="fa-solid fa-arrow-up"></i></a>
</div>

<script src="{{ asset('js/app.js') }}?v={{ @filemtime(public_path('js/app.js')) ?: 1 }}"></script>
</body>
</html>
