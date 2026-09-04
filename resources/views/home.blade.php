@extends('layouts.frontend')

@section('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Organization",
    "name": "{{ $contents['site_name'] ?? 'CoreArsitek' }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset($contents['logo_image'] ?? 'images/logo.png') }}",
    "image": "{{ asset($contents['og_image'] ?? 'images/og-image.jpg') }}",
    "description": "{{ $contents['meta_description'] ?? '' }}",
    "contactPoint": {
        "@@type": "ContactPoint",
        "telephone": "{{ $contents['contact_phone'] ?? '' }}",
        "email": "{{ $contents['contact_email'] ?? '' }}",
        "contactType": "customer service"
    }
}
</script>
@endsection

@section('content')

{{-- ================= HERO ================= --}}
<section id="beranda" class="hero" style="background-image: url('{{ asset($contents['hero_image'] ?? '') }}');">
    <div class="hero-overlay"></div>
    @if (!empty($contents['award_badge']))
        <div class="hero-badge">
            <i class="fa-solid fa-award"></i>
            <span>{!! nl2br(e($contents['award_badge'])) !!}</span>
        </div>
    @endif
    <div class="container hero-content">
        <img src="{{ asset($contents['logo_image'] ?? 'images/logo.png') }}"
             alt="{{ $contents['site_name'] ?? 'CoreArsitek' }}" class="hero-logo reveal-scale">
        <h1 class="hero-title reveal" data-reveal-delay="120">{{ $contents['hero_title'] ?? '' }}</h1>
        <p class="hero-subtitle reveal" data-reveal-delay="220">{{ $contents['hero_subtitle'] ?? '' }}</p>
        <div class="hero-actions reveal" data-reveal-delay="320">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI GRATIS
            </a>
            <a href="{{ route('portfolio') }}" class="btn btn-outline-red magnetic" data-magnetic="0.1">
                <i class="fa-solid fa-images"></i> LIHAT PORTOFOLIO
            </a>
        </div>
    </div>
    <a href="#keunggulan" class="scroll-hint" aria-label="Gulir ke bawah"><i class="fa-solid fa-chevron-down"></i></a>
</section>

{{-- ================= STATISTIK & CTA ================= --}}
<section class="services">
    <div class="container">
        @php
            $statsNumber = $contents['stats_number'] ?? '2707';
            // Hanya animasikan hitungan naik bila nilainya memang angka murni.
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

        <div class="section-cta reveal">
            <a href="{{ route('services') }}" class="btn btn-outline-red magnetic" data-magnetic="0.1"><i class="fa-solid fa-hammer"></i> LIHAT SEMUA LAYANAN</a>
        </div>
    </div>
</section>

{{-- ================= KEUNGGULAN ================= --}}
<section id="keunggulan" class="features">
    <div class="container">
        <h2 class="section-title dark reveal">APA YANG ANDA DAPATKAN?</h2>
        <div class="features-grid" data-reveal-group="40">
            @foreach ($features as $feature)
                <div class="feature-item reveal">
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
        <h2 class="section-title reveal">HUBUNGI KAMI</h2>
        <div class="contact-grid" data-reveal-group="90">
            <div class="contact-item reveal" data-tilt="4">
                <i class="fa-solid fa-location-dot"></i>
                <h3>Alamat</h3>
                <p>{{ $contents['contact_address'] ?? '' }}</p>
            </div>
            <div class="contact-item reveal" data-tilt="4">
                <i class="fa-solid fa-phone"></i>
                <h3>Telepon</h3>
                <p>{{ $contents['contact_phone'] ?? '' }}</p>
            </div>
            <div class="contact-item reveal" data-tilt="4">
                <i class="fa-solid fa-envelope"></i>
                <h3>Email</h3>
                <p>{{ $contents['contact_email'] ?? '' }}</p>
            </div>
        </div>
        <div class="contact-cta reveal">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI GRATIS VIA WHATSAPP
            </a>
        </div>
    </div>
</section>

@endsection
