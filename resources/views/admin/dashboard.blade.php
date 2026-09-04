@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="page-title">Dashboard</h1>

    <div class="stat-grid">
        <a class="stat-card" href="{{ route('admin.banners.index') }}">
            <i class="fa-solid fa-panorama"></i>
            <span class="stat-value">{{ $bannersCount }}</span>
            <span class="stat-label">Banner</span>
        </a>
        <a class="stat-card" href="{{ route('admin.contents.edit') }}">
            <i class="fa-solid fa-file-lines"></i>
            <span class="stat-value">{{ $contentsCount }}</span>
            <span class="stat-label">Konten Situs</span>
        </a>
        <a class="stat-card" href="{{ route('admin.services.index') }}">
            <i class="fa-solid fa-hammer"></i>
            <span class="stat-value">{{ $servicesCount }}</span>
            <span class="stat-label">Layanan</span>
        </a>
        <a class="stat-card" href="{{ route('admin.features.index') }}">
            <i class="fa-solid fa-gem"></i>
            <span class="stat-value">{{ $featuresCount }}</span>
            <span class="stat-label">Keunggulan</span>
        </a>
        <a class="stat-card" href="{{ route('admin.galleries.index') }}">
            <i class="fa-solid fa-images"></i>
            <span class="stat-value">{{ $galleriesCount }}</span>
            <span class="stat-label">Foto Galeri</span>
        </a>
        <a class="stat-card" href="{{ route('admin.process-steps.index') }}">
            <i class="fa-solid fa-diagram-project"></i>
            <span class="stat-value">{{ $stepsCount }}</span>
            <span class="stat-label">Proses Kerja</span>
        </a>
        <a class="stat-card" href="{{ route('admin.testimonials.index') }}">
            <i class="fa-solid fa-quote-left"></i>
            <span class="stat-value">{{ $testimonialsCount }}</span>
            <span class="stat-label">Testimoni</span>
        </a>
    </div>

    <div class="card">
        <h2>Aksi Cepat</h2>
        <div class="quick-actions">
            <a class="btn btn-red" href="{{ route('admin.banners.index') }}"><i class="fa-solid fa-panorama"></i> Kelola Banner Hero</a>
            <a class="btn btn-outline-red" href="{{ route('admin.contents.edit') }}"><i class="fa-solid fa-pen"></i> Ubah Konten Situs</a>
            <a class="btn btn-outline-red" href="{{ route('admin.galleries.index') }}"><i class="fa-solid fa-upload"></i> Tambah Foto Galeri</a>
            <a class="btn btn-outline-red" href="{{ route('admin.services.index') }}"><i class="fa-solid fa-hammer"></i> Kelola Layanan</a>
            <a class="btn btn-outline-red" href="{{ route('home') }}" target="_blank"><i class="fa-solid fa-globe"></i> Lihat Situs</a>
        </div>
    </div>

    <div class="card">
        <h2>Panduan Singkat</h2>
        <ul class="guide-list">
            <li><strong>Banner</strong> — kelola slider di bagian paling atas halaman depan: gambar, judul, subjudul, badge, dan tombol. Bisa lebih dari satu, urutannya diatur lewat kolom Urutan.</li>
            <li><strong>Konten Situs</strong> — ubah logo, judul hero cadangan, angka statistik, teks tentang kami, dan info kontak.</li>
            <li><strong>Layanan</strong> — tambah/edit 6 kartu layanan (ikon, judul, deskripsi).</li>
            <li><strong>Keunggulan</strong> — kelola 24 fitur pada bagian "Apa yang Anda Dapatkan?".</li>
            <li><strong>Galeri</strong> — unggah foto portofolio beserta judul dan deskripsinya. Delapan foto teratas juga tampil di halaman depan.</li>
            <li><strong>Proses Kerja</strong> — tahapan kerja yang tampil berurutan di halaman depan.</li>
            <li><strong>Testimoni</strong> — ulasan klien beserta foto, nama, dan keterangannya.</li>
            <li><strong>Profil</strong> — ubah nama, email, telepon, jabatan, bio, foto profil, dan password Anda.</li>
        </ul>
    </div>
@endsection