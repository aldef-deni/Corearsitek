@extends('layouts.frontend')

@section('title', $portfolio->title . ' — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($portfolio->description ?: $portfolio->title), 155))

@section('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CreativeWork",
    "name": "{{ $portfolio->title }}",
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

        <h1 class="work-title">{{ $portfolio->title }}</h1>

        <p class="work-meta">
            @if ($portfolio->project_date)
                <span><i class="fa-regular fa-calendar"></i> {{ $portfolio->project_date->translatedFormat('d F Y') }}</span>
            @endif
            @if ($portfolio->location)
                <span><i class="fa-solid fa-location-dot"></i> {{ $portfolio->location }}</span>
            @endif
            @if ($portfolio->style)
                <span><i class="fa-solid fa-palette"></i> {{ $portfolio->style }}</span>
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
                <h2 class="book-cover-title">{{ $portfolio->title }}</h2>
                <dl class="book-cover-specs">
                    <dt>Kategori</dt><dd>{{ $portfolio->categoryLabel() }}</dd>

                    @if ($portfolio->style)
                        <dt>Gaya</dt><dd>{{ $portfolio->style }}</dd>
                    @endif
                    @if ($portfolio->client)
                        <dt>Klien</dt><dd>{{ $portfolio->client }}</dd>
                    @endif
                    @if ($portfolio->location)
                        <dt>Lokasi</dt><dd>{{ $portfolio->location }}</dd>
                    @endif
                    @if ($portfolio->floors)
                        <dt>Lantai</dt><dd>{{ $portfolio->floors }}</dd>
                    @endif
                    @if ($portfolio->building_area)
                        <dt>Luas Bangunan</dt><dd>{{ $angka($portfolio->building_area) }} m&sup2;</dd>
                    @endif
                    @if ($portfolio->land_width && $portfolio->land_length)
                        <dt>Dimensi Lahan</dt>
                        <dd>{{ $angka($portfolio->land_width) }} m &times; {{ $angka($portfolio->land_length) }} m</dd>
                    @endif
                    @if ($portfolio->project_date)
                        <dt>Tanggal</dt><dd>{{ $portfolio->project_date->translatedFormat('d F Y') }}</dd>
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
                        <img src="{{ asset($foto->image) }}" alt="{{ $foto->caption ?: $portfolio->title }}" loading="lazy">
                        @if ($foto->caption)
                            <figcaption>{{ $foto->caption }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </div>

        <div class="work-cta reveal">
            <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI DESAIN SERUPA
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
