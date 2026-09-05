@extends('layouts.frontend')

@section('title', __('situs.galeri') . ' — ' . ($contents['site_name'] ?? 'CoreArsitek'))
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
            <span class="eyebrow">{{ __('situs.eyebrow_galeri') }}</span>
            <h2 class="section-title">{{ __('situs.judul_galeri') }}</h2>
            <p class="section-lead">{{ __('situs.lead_galeri') }}</p>
        </div>
    </div>

    @if ($galleries->isEmpty())
        <div class="container">
            <p class="empty-state">{{ __('situs.galeri_kosong') }}</p>
        </div>
    @else
        {{-- Di luar .container supaya deretannya menempel ke tepi layar. --}}
        <div class="gallery-bleed" data-gallery-book
             data-book-logo="{{ asset($logo) }}"
             data-book-name="{{ $contents['site_name'] ?? 'CoreArsitek' }}">
            @foreach ($galleries as $gallery)
                <button type="button" class="gb-item"
                        data-gbook-item
                        data-src="{{ asset($gallery->image) }}"
                        data-title="{{ $gallery->t('title') }}"
                        data-desc="{{ $gallery->t('description') }}"
                        aria-label="{{ __('situs.buka_foto', ['judul' => $gallery->t('title')]) }}">
                    <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->t('title') }}" loading="lazy">
                    <span class="gb-cap">
                        <strong>{{ $gallery->t('title') }}</strong>
                        @if ($gallery->t('description'))
                            <small>{{ $gallery->t('description') }}</small>
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
        <h2 class="section-title reveal">{{ __('situs.seperti_ini') }}</h2>
        <div class="contact-cta reveal">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}"
               target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> {{ __('situs.konsultasi') }}
            </a>
        </div>
    </div>
</section>

@endsection
