@extends('layouts.frontend')

@section('title', $portfolio->t('title') . ' — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($portfolio->t('description') ?: $portfolio->t('title')), 155))

@section('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CreativeWork",
    "name": "{{ $portfolio->t('title') }}",
    "url": "{{ route('portfolio.show', $portfolio) }}",
    "image": "{{ asset($portfolio->cover_image) }}",
    "dateCreated": "{{ $portfolio->project_date?->format('Y-m-d') }}",
    "creator": { "@@type": "Organization", "name": "{{ $contents['site_name'] ?? 'CoreArsitek' }}" }
}
</script>
@endsection

@php
    $logo = $contents['logo_image'] ?? 'images/logo.png';
    $wa = 'https://wa.me/' . preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '');

    // Angka spesifikasi dirapikan: "485.00" jadi "485", "27.65" jadi "27,65".
    $angka = fn ($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');
@endphp

@section('content')

<section class="work-hero">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="{{ route('portfolio') }}">Portofolio</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="{{ route('portfolio', ['kategori' => $portfolio->category]) }}">{{ $portfolio->categoryLabel() }}</a>
        </nav>

        <h1 class="work-title">{{ $portfolio->t('title') }}</h1>

        <p class="work-meta">
            @if ($portfolio->project_date)
                <span><i class="fa-regular fa-calendar"></i> {{ $portfolio->project_date->translatedFormat('d F Y') }}</span>
            @endif
            @if ($portfolio->t('location'))
                <span><i class="fa-solid fa-location-dot"></i> {{ $portfolio->t('location') }}</span>
            @endif
            @if ($portfolio->t('style'))
                <span><i class="fa-solid fa-palette"></i> {{ $portfolio->t('style') }}</span>
            @endif
        </p>
    </div>
</section>

<section class="work-book">
    <div class="container">
        {{-- Daftar foto ini sekaligus jadi sumber flipbook. JavaScript merakitnya
             jadi buku; tanpa JS daftarnya tetap tampil utuh. --}}
        <div class="work-photos" data-flipbook>

            {{-- Halaman sampul, dipasang di lembar kiri saat buku masih tertutup. --}}
            <template data-flip-cover>
                <img src="{{ asset($logo) }}" alt="{{ $contents['site_name'] ?? 'CoreArsitek' }}" class="book-logo">
                <h2 class="book-cover-title">{{ $portfolio->t('title') }}</h2>
                <dl class="book-cover-specs">
                    <dt>{{ __('situs.kategori') }}</dt><dd>{{ $portfolio->categoryLabel() }}</dd>

                    @if ($portfolio->t('style'))
                        <dt>{{ __('situs.gaya') }}</dt><dd>{{ $portfolio->t('style') }}</dd>
                    @endif
                    @if ($portfolio->client)
                        <dt>{{ __('situs.klien') }}</dt><dd>{{ $portfolio->client }}</dd>
                    @endif
                    @if ($portfolio->t('location'))
                        <dt>{{ __('situs.lokasi') }}</dt><dd>{{ $portfolio->t('location') }}</dd>
                    @endif
                    @if ($portfolio->floors)
                        <dt>{{ __('situs.lantai') }}</dt><dd>{{ $portfolio->floors }}</dd>
                    @endif
                    @if ($portfolio->building_area)
                        <dt>{{ __('situs.luas_bangunan') }}</dt><dd>{{ $angka($portfolio->building_area) }} m&sup2;</dd>
                    @endif
                    @if ($portfolio->land_width && $portfolio->land_length)
                        <dt>{{ __('situs.dimensi_lahan') }}</dt>
                        <dd>{{ $angka($portfolio->land_width) }} m &times; {{ $angka($portfolio->land_length) }} m</dd>
                    @endif
                    @if ($portfolio->project_date)
                        <dt>{{ __('situs.tanggal') }}</dt><dd>{{ $portfolio->project_date->translatedFormat('d F Y') }}</dd>
                    @endif
                </dl>
            </template>

            {{-- Halaman penutup di akhir buku. --}}
            <template data-flip-end>
                <img src="{{ asset($logo) }}" alt="{{ $contents['site_name'] ?? 'CoreArsitek' }}"
                     class="book-logo book-logo-pulse">
            </template>

            <div data-flip-list>
                <figure class="work-cover reveal-scale" data-flip-photo>
                    <img src="{{ asset($portfolio->cover_image) }}" alt="{{ $portfolio->title }}">
                </figure>

                @foreach ($portfolio->images as $foto)
                    <figure class="work-photo reveal" data-flip-photo>
                        <img src="{{ asset($foto->image) }}" alt="{{ $foto->t('caption') ?: $portfolio->t('title') }}" loading="lazy">
                        @if ($foto->t('caption'))
                            <figcaption>{{ $foto->t('caption') }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </div>

        <div class="work-cta reveal">
            <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> {{ __('situs.konsultasi_serupa') }}
            </a>
        </div>
    </div>
</section>

@if ($related->count())
    <section class="showcase">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">KARYA LAIN</span>
                <h2 class="section-title">{{ $portfolio->categoryLabel() }}</h2>
            </div>
            <div class="portfolio-grid" data-reveal-group="70">
                @foreach ($related as $item)
                    <article class="portfolio-card reveal">
                        <div class="pc-media">
                            <a href="{{ route('portfolio.show', $item) }}" class="pc-main">
                                <img src="{{ asset($item->cover_image) }}" alt="{{ $item->title }}" loading="lazy">
                                <span class="portfolio-badge">{{ $item->categoryLabel() }}</span>
                            </a>
                        </div>
                        <div class="portfolio-body">
                            <h3><a href="{{ route('portfolio.show', $item) }}">{{ $item->title }}</a></h3>
                            @if ($item->location)
                                <p class="portfolio-meta"><span><i class="fa-solid fa-location-dot"></i> {{ $item->location }}</span></p>
                            @endif
                        </div>
                    </article>
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

@endsection
