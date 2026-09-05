@extends('layouts.frontend')

@section('title', ($contents['about_title'] ?? 'Tentang CoreArsitek') . ' — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($contents['about_text'] ?? ''), 155) ?: 'Tentang ' . ($contents['site_name'] ?? 'CoreArsitek') . ' — biro jasa desain arsitektur untuk hunian mewah.')

@php
    $wa = 'https://wa.me/' . preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '');

    // Misi dan keahlian disimpan satu poin per baris agar mudah disunting admin.
    $baris = fn ($teks) => collect(preg_split('/\r\n|\r|\n/', (string) $teks))
        ->map(fn ($b) => trim($b))
        ->filter()
        ->values();

    $misi = $baris($contents['about_mission'] ?? '');
    $keahlian = $baris($contents['about_profile_skills'] ?? '');

    $adaProfil = ! empty($contents['about_profile_name'])
        || ! empty($contents['about_profile_bio'])
        || ! empty($contents['about_profile_image']);
@endphp

@section('content')

@include('partials.banner', [
    'slides' => $banners,
    'variant' => 'page',
    'fallbackImage' => $contents['hero_image'] ?? '',
    'fallbackTitle' => $contents['about_title'] ?? __('situs.tentang'),
    'fallbackSubtitle' => $contents['hero_subtitle'] ?? '',
    'siteName' => $contents['site_name'] ?? 'CoreArsitek',
])

{{-- ================= PENGANTAR ================= --}}
<section class="about-intro">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">{{ __('situs.eyebrow_tentang') }}</span>
            <h2 class="section-title">{{ $contents['about_title'] ?? __('situs.tentang') }}</h2>
        </div>

        @if (!empty($contents['about_text']))
            <p class="about-lead reveal">{{ $contents['about_text'] }}</p>
        @endif

        @if (!empty($contents['about_tagline']))
            <blockquote class="about-tagline reveal">
                <i class="fa-solid fa-quote-left"></i>
                {{ $contents['about_tagline'] }}
            </blockquote>
        @endif
    </div>
</section>

{{-- ================= VISI & MISI ================= --}}
@if (!empty($contents['about_vision']) || $misi->count())
    <section class="vm">
        <div class="container vm-grid">
            @if (!empty($contents['about_vision']))
                <article class="vm-card reveal-left">
                    <span class="vm-icon"><i class="fa-solid fa-eye"></i></span>
                    <h3>{{ __('situs.visi') }}</h3>
                    <p>{{ $contents['about_vision'] }}</p>
                </article>
            @endif

            @if ($misi->count())
                <article class="vm-card reveal-right">
                    <span class="vm-icon"><i class="fa-solid fa-bullseye"></i></span>
                    <h3>{{ __('situs.misi') }}</h3>
                    <ol class="vm-list">
                        @foreach ($misi as $poin)
                            <li>{{ $poin }}</li>
                        @endforeach
                    </ol>
                </article>
            @endif
        </div>
    </section>
@endif

{{-- ================= PROFIL ================= --}}
@if ($adaProfil)
    <section class="profile">
        {{-- Tanpa foto, kolomnya dilebur jadi satu agar tidak menyisakan kotak kosong. --}}
        <div class="container profile-split {{ empty($contents['about_profile_image']) ? 'profile-split-solo' : '' }}">
            @if (!empty($contents['about_profile_image']))
                <div class="profile-photo reveal-left">
                    <img src="{{ asset($contents['about_profile_image']) }}"
                         alt="{{ $contents['about_profile_name'] ?? __('situs.profil') }}">
                    <span class="profile-tag">{{ __('situs.profil') }}</span>
                </div>
            @endif

            <div class="profile-info reveal-right">
                @if (!empty($contents['about_profile_name']))
                    <h2 class="profile-nama">{{ $contents['about_profile_name'] }}</h2>
                @endif

                @if (!empty($contents['about_profile_role']))
                    <p class="profile-jabatan">{{ $contents['about_profile_role'] }}</p>
                @endif

                @if (!empty($contents['about_profile_quote']))
                    <blockquote class="profile-kutipan">{{ $contents['about_profile_quote'] }}</blockquote>
                @endif

                @if (!empty($contents['about_profile_bio']))
                    <p class="profile-bio">{{ $contents['about_profile_bio'] }}</p>
                @endif

                @if ($keahlian->count())
                    <div class="profile-skills">
                        <h4>{{ __('situs.bidang_keahlian') }}</h4>
                        <ul>
                            @foreach ($keahlian as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif

{{-- ================= ANGKA ================= --}}
<section class="about-band">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">{{ __('situs.eyebrow_angka') }}</span>
            <h2 class="section-title">{{ __('situs.judul_angka') }}</h2>
        </div>
        <div class="about-figures about-figures-wide" data-reveal-group="60">
            <div class="about-figure reveal">
                <span class="figure-value">{{ $contents['stats_number'] ?? '2707' }}+</span>
                <span class="figure-label">{{ __('situs.desain_selesai') }}</span>
            </div>
            <div class="about-figure reveal">
                <span class="figure-value">12+</span>
                <span class="figure-label">{{ __('situs.tahun_pengalaman') }}</span>
            </div>
            <div class="about-figure reveal">
                <span class="figure-value">100%</span>
                <span class="figure-label">{{ __('situs.kepuasan_klien') }}</span>
            </div>
            <div class="about-figure reveal">
                <span class="figure-value">24</span>
                <span class="figure-label">{{ __('situs.item_deliverable') }}</span>
            </div>
        </div>
    </div>
</section>

{{-- ================= KLIEN ================= --}}
@if ($clients->count())
    <section class="clients">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">{{ __('situs.eyebrow_klien') }}</span>
                <h2 class="section-title">{{ __('situs.judul_klien') }}</h2>
            </div>

            <div class="client-grid" data-reveal-group="30">
                @foreach ($clients as $klien)
                    @if ($klien->url)
                        <a class="client-item reveal" href="{{ $klien->url }}" target="_blank" rel="noopener"
                           title="{{ $klien->name }}">
                            <img src="{{ asset($klien->logo) }}" alt="{{ $klien->name }}" loading="lazy">
                        </a>
                    @else
                        <div class="client-item reveal" title="{{ $klien->name }}">
                            <img src="{{ asset($klien->logo) }}" alt="{{ $klien->name }}" loading="lazy">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ================= KONTAK ================= --}}
<section id="kontak" class="contact">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">{{ __('situs.kontak') }}</span>
            <h2 class="section-title">{{ __('situs.judul_hubungi') }}</h2>
        </div>
        <div class="contact-grid" data-reveal-group="90">
            <div class="contact-item reveal" data-tilt="4">
                <i class="fa-solid fa-location-dot"></i>
                <h3>{{ __('situs.alamat') }}</h3>
                <p>{{ $contents['contact_address'] ?? '' }}</p>
            </div>
            <div class="contact-item reveal" data-tilt="4">
                <i class="fa-solid fa-phone"></i>
                <h3>{{ __('situs.telepon') }}</h3>
                <p>{{ $contents['contact_phone'] ?? '' }}</p>
            </div>
            <div class="contact-item reveal" data-tilt="4">
                <i class="fa-solid fa-envelope"></i>
                <h3>{{ __('situs.email') }}</h3>
                <p>{{ $contents['contact_email'] ?? '' }}</p>
            </div>
        </div>
        <div class="contact-cta reveal">
            <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-red btn-flash magnetic" data-magnetic="0.12">
                <i class="fa-brands fa-whatsapp"></i> {{ __('situs.konsultasi') }}
            </a>
        </div>
    </div>
</section>

@endsection
