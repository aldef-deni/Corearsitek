@extends('layouts.frontend')

@section('title', 'Portofolio — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', 'Jelajahi portofolio proyek desain rumah mewah, villa, interior, dan renovasi karya ' . ($contents['site_name'] ?? 'CoreArsitek') . '.')

@section('content')

<section class="page-banner" style="background-image: url('{{ asset($contents['hero_image'] ?? '') }}');">
    <div class="hero-overlay"></div>
    <div class="container banner-content">
        <h1>PORTOFOLIO</h1>
        <p>Karya desain CoreArsitek</p>
    </div>
</section>

<section class="gallery">
    <div class="container">
        <h2 class="section-title">GALERI PROYEK</h2>
        @if ($galleries->isEmpty())
            <p class="empty-state">Belum ada foto galeri. Tambahkan melalui dashboard admin.</p>
        @else
            <div class="gallery-grid">
                @foreach ($galleries as $gallery)
                    <figure class="gallery-item">
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

@endsection