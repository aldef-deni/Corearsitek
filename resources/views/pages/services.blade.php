@extends('layouts.frontend')

@section('title', 'Layanan — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', 'Layanan jasa desain arsitektur ' . ($contents['site_name'] ?? 'CoreArsitek') . ': desain rumah, villa, interior, rumah + interior, bangunan lain, dan renovasi rumah.')

@section('content')

<section class="page-banner" style="background-image: url('{{ asset($contents['hero_image'] ?? '') }}');">
    <div class="hero-overlay"></div>
    <div class="container banner-content">
        <h1>LAYANAN KAMI</h1>
        <p>Jasa desain arsitektur untuk hunian mewah</p>
    </div>
</section>

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

        <div class="services-grid">
            @foreach ($services as $service)
                <div class="service-card">
                    <i class="fa-solid {{ $service->icon }}"></i>
                    <h3>{!! nl2br(e($service->title)) !!}</h3>
                    <p>{{ $service->subtitle }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="contact">
    <div class="container">
        <h2 class="section-title">SIAP MEMULAI PROYEK ANDA?</h2>
        <div class="contact-cta">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" class="btn btn-gold">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI GRATIS VIA WHATSAPP
            </a>
        </div>
    </div>
</section>

@endsection