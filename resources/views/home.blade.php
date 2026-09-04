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

@php
    $wa = 'https://wa.me/' . preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '');


    $statsNumber = $contents['stats_number'] ?? '2707';
@endphp

@section('content')

{{-- ================= BANNER BERANDA ================= --}}
@include('partials.banner', [
    'slides' => $banners,
    'variant' => 'hero',
    'fallbackImage' => $contents['hero_image'] ?? '',
    'fallbackTitle' => $contents['hero_title'] ?? '',
    'fallbackSubtitle' => $contents['hero_subtitle'] ?? '',
    'fallbackBadge' => $contents['award_badge'] ?? '',
    'siteName' => $contents['site_name'] ?? 'CoreArsitek',
])

{{-- ================= PITA LAYANAN ================= --}}
@if ($services->count())
    <section id="layanan" class="service-band">
        <div class="container">
            <div class="service-pills" data-reveal-group="50">
                @foreach ($services as $service)
                    <a href="{{ route('services') }}" class="service-pill reveal">
                        <i class="fa-solid {{ $service->icon }}"></i>
                        <span>{{ $service->title }}</span>
                        <i class="fa-solid fa-arrow-right pill-arrow"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ================= MOZAIK PORTOFOLIO ================= --}}
@if ($galleries->count())
    <section class="showcase">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">PORTOFOLIO</span>
                <h2 class="section-title">DESAIN PILIHAN</h2>
                <p class="section-lead">Sebagian karya yang sudah kami selesaikan — dari hunian klasik hingga villa tropis.</p>
            </div>

            <div class="mosaic" data-reveal-group="70">
                @foreach ($galleries as $i => $gallery)
                    <a href="{{ route('portfolio') }}" class="mosaic-item reveal {{ $i === 0 ? 'mosaic-lead' : '' }}">
                        <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy">
                        <div class="mosaic-caption">
                            <h3>{{ $gallery->title }}</h3>
                            @if ($gallery->description)
                                <p>{{ $gallery->description }}</p>
                            @endif
                        </div>
                        <span class="mosaic-cta"><i class="fa-solid fa-arrow-right"></i></span>
                    </a>
                @endforeach
            </div>

            <div class="section-cta reveal">
                <a href="{{ route('portfolio') }}" class="btn btn-outline-red magnetic" data-magnetic="0.1">
                    <i class="fa-solid fa-images"></i> LIHAT SEMUA PORTOFOLIO
                </a>
            </div>
        </div>
    </section>
@endif

{{-- ================= KEUNGGULAN ================= --}}
@if ($features->count())
    <section id="keunggulan" class="features">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">KEUNGGULAN</span>
                <h2 class="section-title">APA YANG ANDA DAPATKAN?</h2>
            </div>
            <div class="features-grid" data-reveal-group="35">
                @foreach ($features as $feature)
                    <div class="feature-item reveal">
                        <i class="fa-solid {{ $feature->icon }}"></i>
                        <span>{{ $feature->label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ================= PROSES KERJA ================= --}}
@if ($steps->count())
    <section class="process">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">ALUR KERJA</span>
                <h2 class="section-title">PROSES KERJA KAMI</h2>
                <p class="section-lead">Empat tahap yang jelas, dari obrolan pertama sampai gambar kerja di tangan Anda.</p>
            </div>

            <ol class="process-grid" data-reveal-group="90">
                @foreach ($steps as $i => $step)
                    <li class="process-step reveal">
                        <span class="process-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <i class="fa-solid {{ $step->icon }}"></i>
                        <h3>{{ $step->title }}</h3>
                        <p>{{ $step->description }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endif

{{-- ================= TESTIMONI ================= --}}
@if ($testimonials->count())
    <section class="testimonials">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">ULASAN</span>
                <h2 class="section-title">TESTIMONI KLIEN</h2>
            </div>

            <div class="testimonial-grid" data-reveal-group="90">
                @foreach ($testimonials as $testimonial)
                    <figure class="testimonial-card reveal" data-tilt="3">
                        <i class="fa-solid fa-quote-left quote-mark"></i>
                        <blockquote>{{ $testimonial->quote }}</blockquote>
                        <figcaption>
                            @if ($testimonial->avatar)
                                <img src="{{ asset($testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="testimonial-avatar" loading="lazy">
                            @else
                                <span class="testimonial-avatar testimonial-initials">{{ \Illuminate\Support\Str::of($testimonial->name)->explode(' ')->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('') }}</span>
                            @endif
                            <span>
                                <strong>{{ $testimonial->name }}</strong>
                                @if ($testimonial->role)
                                    <small>{{ $testimonial->role }}</small>
                                @endif
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ================= TENTANG ================= --}}
<section class="about-band">
    <div class="container about-split">
        <div class="reveal-left">
            <span class="eyebrow">TENTANG KAMI</span>
            <h2 class="section-title section-title-left">{{ $contents['about_title'] ?? 'TENTANG COREARSITEK' }}</h2>
            <p class="about-copy">{{ $contents['about_text'] ?? '' }}</p>
            <a href="{{ route('about') }}" class="btn btn-outline-red magnetic" data-magnetic="0.1">
                <i class="fa-solid fa-arrow-right"></i> SELENGKAPNYA
            </a>
        </div>
        <div class="about-figures reveal-right">
            <div class="about-figure">
                <span class="figure-value">{{ $statsNumber }}+</span>
                <span class="figure-label">Desain Selesai</span>
            </div>
            <div class="about-figure">
                <span class="figure-value">12+</span>
                <span class="figure-label">Tahun Pengalaman</span>
            </div>
            <div class="about-figure">
                <span class="figure-value">100%</span>
                <span class="figure-label">Kepuasan Klien</span>
            </div>
            <div class="about-figure">
                <span class="figure-value">24</span>
                <span class="figure-label">Item Deliverable</span>
            </div>
        </div>
    </div>
</section>

{{-- ================= KONTAK ================= --}}
<section id="kontak" class="contact">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">KONTAK</span>
            <h2 class="section-title">HUBUNGI KAMI</h2>
        </div>
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
            <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI GRATIS VIA WHATSAPP
            </a>
        </div>
    </div>
</section>

@endsection
