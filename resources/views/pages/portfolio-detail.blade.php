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
            {{-- Daftar foto ini juga jadi sumber flipbook. JavaScript merakitnya
                 jadi buku pada layar lebar; tanpa JS daftarnya tetap tampil utuh. --}}
            <div class="work-photos" data-flipbook data-flipbook-title="{{ $portfolio->title }}">
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
    </div>
</section>

<section class="work-body">
    <div class="container work-split">
        <div class="work-main">
            @if ($portfolio->description)
                <div class="work-card reveal">
                    <h2>Tentang Karya Ini</h2>
                    <p class="work-desc">{{ $portfolio->description }}</p>
                </div>
            @endif
        </div>

        <aside class="work-aside">
            <div class="work-card reveal">
                <h2>Spesifikasi</h2>
                <dl class="work-specs">
                    <dt>Kategori</dt>
                    <dd>{{ $portfolio->categoryLabel() }}</dd>

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
                    @foreach ($portfolio->specs() as $spec)
                        @if (! str_contains($spec, 'lantai'))
                            <dt>{{ str_starts_with($spec, 'LB') ? 'Luas Bangunan' : 'Dimensi Lahan' }}</dt>
                            <dd>{{ str_replace('LB ', '', $spec) }}</dd>
                        @endif
                    @endforeach
                </dl>

                <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}"
                   target="_blank" rel="noopener" class="btn btn-red btn-block magnetic" data-magnetic="0.1">
                    <i class="fa-brands fa-whatsapp"></i> KONSULTASI DESAIN SERUPA
                </a>
            </div>

        </aside>
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
                        <a href="{{ route('portfolio.show', $item) }}" class="portfolio-cover">
                            <img src="{{ asset($item->cover_image) }}" alt="{{ $item->title }}" loading="lazy">
                            <span class="portfolio-badge">{{ $item->categoryLabel() }}</span>
                        </a>
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
