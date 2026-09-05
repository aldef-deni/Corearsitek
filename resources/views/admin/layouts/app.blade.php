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
<body data-upload-max-kb="{{ \App\Support\UploadHelper::MAX_UPLOAD_KB }}"
      data-upload-total-kb="{{ \App\Support\UploadHelper::MAX_TOTAL_KB }}"
      data-upload-max-batch="{{ \App\Support\UploadHelper::MAX_BATCH }}">
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
        <a href="{{ route('admin.about.edit') }}" class="{{ request()->routeIs('admin.about.*') || request()->routeIs('admin.clients.*') ? 'active' : '' }}">
            <i class="fa-regular fa-building"></i> Tentang CoreArsitek
        </a>
        <a href="{{ route('admin.contents.edit') }}" class="{{ request()->routeIs('admin.contents.*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines"></i> Konten Situs
        </a>
        <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
            <i class="fa-solid fa-panorama"></i> Banner
        </a>
        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
            <i class="fa-solid fa-hammer"></i> Layanan
        </a>
        <a href="{{ route('admin.features.index') }}" class="{{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
            <i class="fa-solid fa-gem"></i> Keunggulan
        </a>
        <a href="{{ route('admin.portfolios.index') }}" class="{{ request()->routeIs('admin.portfolios.*') ? 'active' : '' }}">
            <i class="fa-solid fa-building-columns"></i> Portofolio
        </a>
        <a href="{{ route('admin.galleries.index') }}" class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
            <i class="fa-solid fa-images"></i> Galeri
        </a>
        <a href="{{ route('admin.benefit.index') }}" class="{{ request()->routeIs('admin.benefit.*') ? 'active' : '' }}">
            <i class="fa-solid fa-scale-balanced"></i> Benefit CoreArsitek
        </a>
        <a href="{{ route('admin.process-steps.index') }}" class="{{ request()->routeIs('admin.process-steps.*') ? 'active' : '' }}">
            <i class="fa-solid fa-diagram-project"></i> Proses Kerja
        </a>
        <a href="{{ route('admin.submissions.index') }}" class="{{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}">
            <i class="fa-solid fa-inbox"></i> Data Pengajuan
            @if ($pengajuanBaru = \App\Models\Submission::unread()->count())
                <span class="nav-badge">{{ $pengajuanBaru > 99 ? '99+' : $pengajuanBaru }}</span>
            @endif
        </a>
        <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
            <i class="fa-solid fa-quote-left"></i> Testimoni
        </a>
        <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <i class="fa-regular fa-id-badge"></i> Profil
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
        <a class="topbar-user" href="{{ route('admin.profile.edit') }}" title="Buka profil">
            @if (Auth::user()->avatar)
                <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="topbar-avatar">
            @else
                <span class="topbar-avatar topbar-avatar-initials">{{ \Illuminate\Support\Str::of(Auth::user()->name)->explode(' ')->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('') }}</span>
            @endif
            <span class="topbar-name">
                {{ Auth::user()->name }}
                <small>{{ Auth::user()->position ?: 'Administrator' }}</small>
            </span>
        </a>

        {{-- Keluar juga ada di kaki menu samping, tapi di sana letaknya jauh di
             bawah dan tidak terlihat tanpa menggulir. Yang ini selalu tampak. --}}
        <form method="POST" action="{{ route('admin.logout') }}"
              onsubmit="return confirm('Keluar dari dashboard?')">
            @csrf
            <button type="submit" class="topbar-logout" title="Keluar dari dashboard">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar</span>
            </button>
        </form>
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
<script src="{{ asset('js/admin.js') }}?v={{ @filemtime(public_path('js/admin.js')) ?: 1 }}"></script>
</body>
</html>