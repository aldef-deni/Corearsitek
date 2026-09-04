@extends('layouts.frontend')

@php
    $namaKategori = $category ? (\App\Models\Portfolio::CATEGORIES[$category] ?? null) : null;
@endphp

@section('title', ($namaKategori ?: 'Portofolio') . ' — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', 'Jelajahi portofolio proyek desain rumah mewah, villa, interior, dan renovasi karya ' . ($contents['site_name'] ?? 'CoreArsitek') . '.')

@section('content')

@include('partials.banner', [
    'slides' => $banners,
    'variant' => 'page',
    'fallbackImage' => $contents['hero_image'] ?? '',
    'fallbackTitle' => 'PORTOFOLIO',
    'fallbackSubtitle' => 'Karya desain CoreArsitek',
    'siteName' => $contents['site_name'] ?? 'CoreArsitek',
])

{{-- ================= KARYA PILIHAN ================= --}}
@if ($featured->count())
    <section class="featured-strip">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">KARYA PILIHAN</span>
                <h2 class="section-title">DESAIN TERBAIK</h2>
            </div>
            <div class="featured-row" data-reveal-group="60">
                @foreach ($featured as $item)
                    <a href="{{ route('portfolio.show', $item) }}" class="featured-item reveal">
                        <img src="{{ asset($item->cover_image) }}" alt="{{ $item->title }}" loading="lazy">
                        <span class="featured-name">{{ $item->client ?: $item->title }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ================= DAFTAR KARYA ================= --}}
<section class="portfolio-list">
    <div class="container">

        <nav class="filter-tabs" aria-label="Saring portofolio">
            <a href="{{ route('portfolio') }}" class="filter-tab {{ $category ? '' : 'is-active' }}">
                Semua Portofolio <span>{{ $total }}</span>
            </a>
            @foreach (\App\Models\Portfolio::CATEGORIES as $key => $label)
                @if (($counts[$key] ?? 0) > 0)
                    <a href="{{ route('portfolio', ['kategori' => $key]) }}"
                       class="filter-tab {{ $category === $key ? 'is-active' : '' }}">
                        {{ $label }} <span>{{ $counts[$key] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        @if ($portfolios->total())
            <p class="result-count">
                <strong>{{ $portfolios->total() }}</strong> karya
                @if ($namaKategori) pada kategori <strong>{{ $namaKategori }}</strong> @endif
                @if ($portfolios->lastPage() > 1)
                    (halaman <strong>{{ $portfolios->currentPage() }}</strong> dari <strong>{{ $portfolios->lastPage() }}</strong>)
                @endif
            </p>

            <div class="portfolio-grid" data-reveal-group="60">
                @foreach ($portfolios as $item)
                    <article class="portfolio-card reveal">
                        <a href="{{ route('portfolio.show', $item) }}" class="portfolio-cover">
                            <img src="{{ asset($item->cover_image) }}" alt="{{ $item->title }}" loading="lazy">
                            <span class="portfolio-badge">{{ $item->categoryLabel() }}</span>
                        </a>

                        @if ($item->images->count())
                            <div class="portfolio-thumbs">
                                @foreach ($item->images->take(3) as $foto)
                                    <a href="{{ route('portfolio.show', $item) }}">
                                        <img src="{{ asset($foto->image) }}" alt="{{ $item->title }}" loading="lazy">
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <div class="portfolio-body">
                            <h3><a href="{{ route('portfolio.show', $item) }}">{{ $item->title }}</a></h3>

                            <p class="portfolio-meta">
                                @if ($item->project_date)
                                    <span>{{ $item->project_date->translatedFormat('d F Y') }}</span>
                                @endif
                                @if ($item->location)
                                    <span><i class="fa-solid fa-location-dot"></i> {{ $item->location }}</span>
                                @endif
                            </p>

                            @if ($item->specs())
                                <ul class="portfolio-specs">
                                    @foreach ($item->specs() as $spec)
                                        <li><i class="fa-solid fa-check"></i> {{ $spec }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($portfolios->hasPages())
                <div class="pagination-wrap">{{ $portfolios->onEachSide(1)->links() }}</div>
            @endif
        @else
            <p class="empty-state">
                @if ($category)
                    Belum ada karya pada kategori ini.
                    <a href="{{ route('portfolio') }}">Lihat semua portofolio</a>.
                @else
                    Belum ada karya yang ditampilkan. Tambahkan melalui dashboard admin.
                @endif
            </p>
        @endif
    </div>
</section>

{{-- ================= CTA ================= --}}
<section class="contact">
    <div class="container">
        <h2 class="section-title reveal">TERTARIK DENGAN GAYA DESAIN KAMI?</h2>
        <div class="contact-cta reveal">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}"
               target="_blank" rel="noopener" class="btn btn-red magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI
            </a>
        </div>
    </div>
</section>

@endsection
