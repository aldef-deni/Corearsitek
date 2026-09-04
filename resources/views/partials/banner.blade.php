{{--
    Banner yang dipakai bersama Beranda dan halaman dalam.

    Parameter:
      $slides           Collection Banner untuk halaman ini (boleh kosong)
      $variant          'hero' untuk Beranda (16:9), 'page' untuk halaman dalam (lebih pendek)
      $fallbackImage    Gambar cadangan saat admin belum menambah banner
      $fallbackTitle    Judul cadangan (opsional)
      $fallbackSubtitle Subjudul cadangan (opsional)
      $fallbackBadge    Teks badge cadangan (opsional)
      $siteName         Dipakai sebagai alt gambar bila banner tanpa judul
--}}
@php
    $variant = $variant ?? 'hero';
    $siteName = $siteName ?? 'CoreArsitek';

    // Halaman tidak pernah tampil tanpa banner: kalau admin belum mengisi,
    // pakai gambar dan judul cadangan dari Konten Situs.
    $daftar = ($slides ?? collect())->count() ? $slides : collect([(object) [
        'image' => $fallbackImage ?? '',
        'title' => $fallbackTitle ?? '',
        'subtitle' => $fallbackSubtitle ?? '',
        'badge_text' => $fallbackBadge ?? '',
        'button_text' => null,
        'button_url' => null,
    ]]);

    $banyak = $daftar->count() > 1;
@endphp

<section @if ($variant === 'hero') id="beranda" @endif class="hero-slider {{ $variant === 'page' ? 'is-page' : '' }}"
         @if ($banyak) data-slider data-interval="6500" @endif>
    <div class="slides">
        @foreach ($daftar as $i => $slide)
            @php
                // Banner tanpa teks tidak butuh overlay gelap; itu hanya
                // meredupkan fotonya tanpa alasan.
                $adaTeks = $slide->title || $slide->subtitle || $slide->button_text;
            @endphp
            <article class="slide {{ $i === 0 ? 'is-active' : '' }} {{ $adaTeks ? '' : 'slide-plain' }}" data-slide>
                <img src="{{ asset($slide->image) }}" alt="{{ $slide->title ?: $siteName }}"
                     class="slide-img" {{ $i === 0 ? '' : 'loading=lazy' }}>
                <div class="hero-overlay"></div>

                @if (!empty($slide->badge_text))
                    <div class="hero-badge">
                        <i class="fa-solid fa-award"></i>
                        <span>{!! nl2br(e($slide->badge_text)) !!}</span>
                    </div>
                @endif

                <div class="container hero-content">
                    @if (!empty($slide->title))
                        <h1 class="hero-title">{{ $slide->title }}</h1>
                    @endif
                    @if (!empty($slide->subtitle))
                        <p class="hero-subtitle">{{ $slide->subtitle }}</p>
                    @endif
                    {{-- Hanya tombol yang diisi admin lewat menu Banner. --}}
                    @if (!empty($slide->button_text))
                        <div class="hero-actions">
                            <a href="{{ $slide->button_url ?: '#kontak' }}" class="btn btn-red magnetic" data-magnetic="0.12">
                                <i class="fa-solid fa-arrow-right"></i> {{ $slide->button_text }}
                            </a>
                        </div>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

    @if ($banyak)
        <button class="slider-nav slider-prev" data-slider-prev aria-label="Banner sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="slider-nav slider-next" data-slider-next aria-label="Banner berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
        <div class="slider-dots" data-slider-dots>
            @foreach ($daftar as $i => $slide)
                <button class="slider-dot {{ $i === 0 ? 'is-active' : '' }}" data-slider-dot="{{ $i }}" aria-label="Banner {{ $i + 1 }}"></button>
            @endforeach
        </div>
    @endif

    @if ($variant === 'hero')
        <a href="#layanan" class="scroll-hint" aria-label="Gulir ke bawah"><i class="fa-solid fa-chevron-down"></i></a>
    @endif
</section>
