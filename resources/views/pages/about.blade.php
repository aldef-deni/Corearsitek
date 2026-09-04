@extends('layouts.frontend')

@section('title', 'Tentang CoreArsitek — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', 'Tentang ' . ($contents['site_name'] ?? 'CoreArsitek') . ' — biro jasa desain arsitektur untuk hunian mewah. Hunian aman, nyaman, dan elegan dengan desain detail dari denah 2D hingga render 3D.')

@section('content')

<section class="page-banner" style="background-image: url('{{ asset($contents['hero_image'] ?? '') }}');">
    <div class="hero-overlay"></div>
    <div class="container banner-content">
        <h1>{{ $contents['about_title'] ?? 'TENTANG COREARSITEK' }}</h1>
        <p>Hunian aman, nyaman, dan elegan</p>
    </div>
</section>

<section class="about">
    <div class="container">
        <p class="about-text">{{ $contents['about_text'] ?? '' }}</p>
        <div class="about-stats">
            <div class="about-stat">
                <span class="stat-value">{{ $contents['stats_number'] ?? '2707' }}+</span>
                <span class="stat-label">Desain Selesai</span>
            </div>
            <div class="about-stat">
                <span class="stat-value">12+</span>
                <span class="stat-label">Tahun Pengalaman</span>
            </div>
            <div class="about-stat">
                <span class="stat-value">100%</span>
                <span class="stat-label">Kepuasan Klien</span>
            </div>
        </div>
    </div>
</section>

<section id="kontak" class="contact">
    <div class="container">
        <h2 class="section-title">HUBUNGI KAMI</h2>
        <div class="contact-grid">
            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <h3>Alamat</h3>
                <p>{{ $contents['contact_address'] ?? '' }}</p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <h3>Telepon</h3>
                <p>{{ $contents['contact_phone'] ?? '' }}</p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-envelope"></i>
                <h3>Email</h3>
                <p>{{ $contents['contact_email'] ?? '' }}</p>
            </div>
        </div>
        <div class="contact-cta">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '') }}" target="_blank" class="btn btn-gold">
                <i class="fa-brands fa-whatsapp"></i> KONSULTASI GRATIS VIA WHATSAPP
            </a>
        </div>
    </div>
</section>

@endsection