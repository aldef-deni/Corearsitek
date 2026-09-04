@extends('layouts.frontend')

@section('title', 'Galeri — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', 'Galeri foto karya dan kegiatan ' . ($contents['site_name'] ?? 'CoreArsitek') . '.')

@section('content')

@include('partials.banner', [
    'slides' => $banners,
    'variant' => 'page',
    'fallbackImage' => $contents['hero_image'] ?? '',
    'fallbackTitle' => 'GALERI',
    'fallbackSubtitle' => 'Dokumentasi karya CoreArsitek',
    'siteName' => $contents['site_name'] ?? 'CoreArsitek',
])

<section class="gallery">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">DOKUMENTASI</span>
            <h2 class="section-title">GALERI FOTO</h2>
            <p class="section-lead">Kumpulan foto karya dan kegiatan kami.</p>
        </div>

        @if ($galleries->isEmpty())
            <p class="empty-state">Belum ada foto galeri. Tambahkan melalui dashboard admin pada menu Galeri.</p>
        @else
            <div class="gallery-grid" data-reveal-group="70">
                @foreach ($galleries as $gallery)
                    <figure class="gallery-item reveal">
                        <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy">
                        <figcaption>
                            <h3>{{ $gallery->title }}</h3>
                            @if ($gallery->description)
                                <p>{{ $gallery->description }}</p>
                            @endif
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="contact">
    <div class="container">
        <h2 class="section-title reveal">INGIN KARYA SEPERTI INI?</h2>
        <div class="contact-cta reveal">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}"
               target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI GRATIS VIA WHATSAPP
            </a>
        </div>
    </div>
</section>

@endsection
