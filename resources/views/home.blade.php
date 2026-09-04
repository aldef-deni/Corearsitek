@extends('layouts.frontend')

@section('content')

{{-- ================= HERO ================= --}}
<section id="beranda" class="hero" style="background-image: url('{{ asset($contents['hero_image'] ?? '') }}');">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <div class="hero-badge">
            <i class="fa-solid fa-crown"></i>
            <span>{!! nl2br(e($contents['award_badge'] ?? '')) !!}</span>
        </div>
        <h1 class="hero-title">{{ $contents['hero_title'] ?? '' }}</h1>
        <p class="hero-subtitle">{{ $contents['hero_subtitle'] ?? '' }}</p>
    </div>
</section>

{{-- ================= STATISTIK & CTA ================= --}}
<section class="services">
    <div class="container">
        <div class="stats-card">
            <div class="stats-number">{{ $contents['stats_number'] ?? '2707' }}</div>
            <div class="stats-label">{{ $contents['stats_label'] ?? 'Desain Finish s.d. 04-09-2026' }}</div>
            <div class="stats-actions">
                <a href="{{ route('portfolio') }}" class="btn btn-outline-gold"><i class="fa-solid fa-house"></i> DESAIN TERBAIK</a>
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" class="btn btn-gold"><i class="fa-brands fa-whatsapp"></i> KONTAK KAMI</a>
            </div>
        </div>

        <div class="section-cta">
            <a href="{{ route('services') }}" class="btn btn-outline-gold"><i class="fa-solid fa-hammer"></i> LIHAT SEMUA LAYANAN</a>
        </div>
    </div>
</section>

{{-- ================= KEUNGGULAN ================= --}}
<section id="keunggulan" class="features">
    <div class="container">
        <h2 class="section-title dark">APA YANG ANDA DAPATKAN?</h2>
        <div class="features-grid">
            @foreach ($features as $feature)
                <div class="feature-item">
                    <i class="fa-solid {{ $feature->icon }}"></i>
                    <span>{{ $feature->label }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= KONTAK ================= --}}
<section id="kontak" class="contact">
    <div class="container">
        <h2 class="section-title">HUBUNGI KAMI</h2>
        <div class="contact-grid">
            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <h3>Alamat</h3>
                <p>{{ $contents['contact_address'] ?? '' }}</p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <h3>Telepon</h3>
                <p>{{ $contents['contact_phone'] ?? '' }}</p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-envelope"></i>
                <h3>Email</h3>
                <p>{{ $contents['contact_email'] ?? '' }}</p>
            </div>
        </div>
        <div class="contact-cta">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" class="btn btn-gold">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI GRATIS VIA WHATSAPP
            </a>
        </div>
    </div>
</section>

@endsection