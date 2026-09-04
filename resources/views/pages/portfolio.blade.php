@extends('layouts.frontend')

@section('title', 'Portofolio — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', 'Jelajahi portofolio proyek desain rumah mewah, villa, interior, dan renovasi karya ' . ($contents['site_name'] ?? 'CoreArsitek') . '.')

@section('content')

<section class="page-banner" style="background-image: url('{{ asset($contents['hero_image'] ?? '') }}');">
    <div class="hero-overlay"></div>
    <div class="container banner-content">
        <h1 class="reveal">PORTOFOLIO</h1>
        <p class="reveal" data-reveal-delay="120">Karya desain CoreArsitek</p>
    </div>
</section>

<section class="gallery">
    <div class="container">
        <h2 class="section-title reveal">GALERI PROYEK</h2>
        @if ($galleries->isEmpty())
            <p class="empty-state">Belum ada foto galeri. Tambahkan melalui dashboard admin.</p>
        @else
            <div class="gallery-grid" data-reveal-group="80">
                @foreach ($galleries as $gallery)
                    <figure class="gallery-item reveal">
                        <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy">
                        <figcaption>
                            <h3>{{ $gallery->title }}</h3>
                            <p>{{ $gallery->description }}</p>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="contact">
    <div class="container">
        <h2 class="section-title reveal">TERTARIK DENGAN GAYA DESAIN KAMI?</h2>
        <div class="contact-cta reveal">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI GRATIS VIA WHATSAPP
            </a>
        </div>
    </div>
</section>

@endsection
