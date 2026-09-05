@extends('layouts.frontend')

@php
    $namaKategori = $category && isset(\App\Models\Portfolio::CATEGORIES[$category])
        ? __('situs.kategori_karya.' . $category)
        : null;
@endphp

@section('title', ($namaKategori ?: __('situs.portofolio')) . ' — ' . ($contents['site_name'] ?? 'CoreArsitek'))
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

{{-- ================= DAFTAR KARYA ================= --}}
<section class="portfolio-list">
    <div class="container">

        <nav class="filter-tabs" aria-label="{{ __('situs.saring_portofolio') }}">
            <a href="{{ route('portfolio') }}" class="filter-tab {{ $category ? '' : 'is-active' }}">
                {{ __('situs.semua_portofolio') }} <span>{{ $total }}</span>
            </a>
            @foreach (\App\Models\Portfolio::CATEGORIES as $key => $label)
                @if (($counts[$key] ?? 0) > 0)
                    <a href="{{ route('portfolio', ['kategori' => $key]) }}"
                       class="filter-tab {{ $category === $key ? 'is-active' : '' }}">
                        {{ __('situs.kategori_karya.' . $key) }} <span>{{ $counts[$key] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        @if ($portfolios->total())
            <p class="result-count">
                <strong>{{ $portfolios->total() }}</strong> {{ __('situs.karya') }}
                @if ($namaKategori) {{ __('situs.pada_kategori') }} <strong>{{ $namaKategori }}</strong> @endif
                @if ($portfolios->lastPage() > 1)
                    ({{ __('situs.halaman_ke') }} <strong>{{ $portfolios->currentPage() }}</strong> {{ __('situs.dari') }} <strong>{{ $portfolios->lastPage() }}</strong>)
                @endif
            </p>

            <div class="portfolio-grid" data-reveal-group="60">
                @foreach ($portfolios as $item)
                    @php $pendamping = $item->images->take(2); @endphp
                    <article class="portfolio-card reveal">
                        <div class="pc-media">
                            <a href="{{ route('portfolio.show', $item) }}" class="pc-main">
                                <img src="{{ asset($item->cover_image) }}" alt="{{ $item->t('title') }}" loading="lazy">
                                <span class="portfolio-badge">{{ $item->categoryLabel() }}</span>
                            </a>

                            @foreach ($pendamping as $foto)
                                {{-- Bila hanya ada satu foto pendamping, ia melebar penuh
                                     agar tidak menyisakan ruang kosong di sampingnya. --}}
                                <a href="{{ route('portfolio.show', $item) }}"
                                   class="pc-sub {{ $pendamping->count() === 1 ? 'pc-sub-wide' : '' }}">
                                    <img src="{{ asset($foto->image) }}" alt="{{ $item->t('title') }}" loading="lazy">
                                </a>
                            @endforeach
                        </div>

                        <div class="portfolio-body">
                            <h3><a href="{{ route('portfolio.show', $item) }}">{{ $item->t('title') }}</a></h3>

                            <p class="portfolio-meta">
                                @if ($item->project_date)
                                    <span>{{ $item->project_date->translatedFormat('d F Y') }}</span>
                                @endif
                                @if ($item->location)
                                    <span><i class="fa-solid fa-location-dot"></i> {{ $item->t('location') }}</span>
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
                    {{ __('situs.belum_ada_karya') }}
                    <a href="{{ route('portfolio') }}">{{ __('situs.lihat_semua_karya') }}</a>.
                @else
                    {{ __('situs.belum_ada_karya_apa_pun') }}
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
