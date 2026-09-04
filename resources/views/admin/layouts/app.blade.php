<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    <meta name="theme-color" content="#060607">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" aria-label="CoreArsitek Admin">
            <img src="{{ asset('images/logo.png') }}" alt="CoreArsitek" class="sidebar-logo">
        </a>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
        <a href="{{ route('admin.contents.edit') }}" class="{{ request()->routeIs('admin.contents.*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines"></i> Konten Situs
        </a>
        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
            <i class="fa-solid fa-hammer"></i> Layanan
        </a>
        <a href="{{ route('admin.features.index') }}" class="{{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
            <i class="fa-solid fa-gem"></i> Keunggulan
        </a>
        <a href="{{ route('admin.galleries.index') }}" class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
            <i class="fa-solid fa-images"></i> Galeri
        </a>
        <a href="{{ route('admin.password.edit') }}" class="{{ request()->routeIs('admin.password.*') ? 'active' : '' }}">
            <i class="fa-solid fa-key"></i> Ubah Password
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="{{ route('home') }}" target="_blank"><i class="fa-solid fa-globe"></i> Lihat Situs</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
        </form>
    </div>
</aside>

<main class="admin-main">
    <header class="topbar">
        <div class="topbar-user">
            <i class="fa-regular fa-circle-user"></i>
            <span>{{ Auth::user()->name }} ({{ Auth::user()->email }})</span>
        </div>
    </header>

    <div class="admin-content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>
</body>
</html>