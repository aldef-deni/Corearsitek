<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $contents['site_name'] ?? 'CoreArsitek')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<header class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-emblem"><i class="fa-solid fa-crown"></i></span>
            <span class="brand-text">{{ $contents['site_name'] ?? 'COREARSITEK' }}</span>
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
            <button class="icon-btn" title="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
            <button class="nav-toggle" id="navToggle"><i class="fa-solid fa-bars"></i></button>
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
                <span class="brand-emblem"><i class="fa-solid fa-crown"></i></span>
                <span class="brand-text">{{ $contents['site_name'] ?? 'COREARSITEK' }}</span>
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
        <div class="container">{{ $contents['footer_copyright'] ?? '© 2026 CoreArsitek. All rights reserved.' }}</div>
    </div>
</footer>

<div class="floating-actions">
    <a class="fab" href="{{ route('portfolio') }}" title="Portofolio"><i class="fa-solid fa-table-cells-large"></i></a>
    <a class="fab" href="{{ route('home') }}#kontak" title="Kontak"><i class="fa-solid fa-magnifying-glass"></i></a>
    <a class="fab" href="{{ route('portfolio') }}" title="Video"><i class="fa-solid fa-play"></i></a>
    <a class="fab" href="#" onclick="sharePage(event)" title="Bagikan"><i class="fa-solid fa-share-nodes"></i></a>
    <a class="fab fab-wa" href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>