@extends('layouts.frontend')

@section('title', 'Layanan — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', 'Layanan jasa desain arsitektur ' . ($contents['site_name'] ?? 'CoreArsitek') . ': desain rumah, villa, interior, rumah + interior, bangunan lain, dan renovasi rumah.')

@section('content')

@include('partials.banner', [
    'slides' => $banners,
    'variant' => 'page',
    'fallbackImage' => $contents['hero_image'] ?? '',
    'fallbackTitle' => 'LAYANAN KAMI',
    'fallbackSubtitle' => 'Jasa desain arsitektur untuk hunian mewah',
    'siteName' => $contents['site_name'] ?? 'CoreArsitek',
])

<section class="services">
    <div class="container">
        @php
            $statsNumber = $contents['stats_number'] ?? '2707';
            $statsCountable = ctype_digit(trim($statsNumber));
        @endphp
        <div class="stats-card reveal-scale" data-tilt="3">
            <div class="stats-number" @if ($statsCountable) data-counter="{{ (int) trim($statsNumber) }}" @endif>{{ $statsNumber }}</div>
            <div class="stats-label">{{ $contents['stats_label'] ?? 'Desain Finish' }}</div>
            <div class="stats-actions">
                <a href="{{ route('portfolio') }}" class="btn btn-outline-red magnetic" data-magnetic="0.1"><i class="fa-solid fa-house"></i> DESAIN TERBAIK</a>
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12"><i class="fa-brands fa-whatsapp"></i> KONTAK KAMI</a>
            </div>
        </div>

        <div class="services-grid" data-reveal-group="80">
            @foreach ($services as $service)
                <div class="service-card reveal" data-tilt="4">
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
        <h2 class="section-title reveal">SIAP MEMULAI PROYEK ANDA?</h2>
        <div class="contact-cta reveal">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI GRATIS VIA WHATSAPP
            </a>
        </div>
    </div>
</section>

@endsection
