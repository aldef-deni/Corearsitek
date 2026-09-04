@extends('layouts.frontend')

@section('title', 'Galeri — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', 'Galeri foto karya dan kegiatan ' . ($contents['site_name'] ?? 'CoreArsitek') . '.')

@php
    $logo = $contents['logo_image'] ?? 'images/logo.png';
@endphp

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
            <p class="section-lead">Kumpulan foto karya dan kegiatan kami. Ketuk foto untuk melihat ukuran penuh.</p>
        </div>

        @if ($galleries->count() > 1)
            {{-- Dua cara membuka foto, bisa dibandingkan langsung. --}}
            <div class="gv-switch reveal" data-gallery-view>
                <span class="gv-label">Cara membuka foto</span>
                <div class="gv-opsi">
                    <button type="button" data-view="buku" aria-pressed="true">
                        <i class="fa-solid fa-book-open"></i> Flipbook
                    </button>
                    <button type="button" data-view="modal" aria-pressed="false">
                        <i class="fa-solid fa-expand"></i> Modal
                    </button>
                </div>
            </div>
        @endif
    </div>

    @if ($galleries->isEmpty())
        <div class="container">
            <p class="empty-state">Belum ada foto galeri. Tambahkan melalui dashboard admin pada menu Galeri.</p>
        </div>
    @else
        {{-- Di luar .container supaya deretannya menempel ke tepi layar. --}}
        <div class="gallery-bleed" data-lightbox-group data-gallery-book
             data-book-logo="{{ asset($logo) }}"
             data-book-name="{{ $contents['site_name'] ?? 'CoreArsitek' }}">
            @foreach ($galleries as $gallery)
                <button type="button" class="gb-item"
                        data-lightbox-item
                        data-src="{{ asset($gallery->image) }}"
                        data-title="{{ $gallery->title }}"
                        data-desc="{{ $gallery->description }}"
                        aria-label="Perbesar foto {{ $gallery->title }}">
                    <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy">
                    <span class="gb-cap">
                        <strong>{{ $gallery->title }}</strong>
                        @if ($gallery->description)
                            <small>{{ $gallery->description }}</small>
                        @endif
                    </span>
                    <span class="gb-zoom" aria-hidden="true"><i class="fa-solid fa-expand"></i></span>
                </button>
            @endforeach
        </div>
    @endif
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
