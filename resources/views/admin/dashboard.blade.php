@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="page-title">Dashboard</h1>

    <div class="stat-grid">
        <a class="stat-card {{ $submissionsUnread ? 'stat-card-alert' : '' }}" href="{{ route('admin.submissions.index') }}">
            <i class="fa-solid fa-inbox"></i>
            <span class="stat-value">{{ $submissionsCount }}</span>
            <span class="stat-label">
                Data Pengajuan
                @if ($submissionsUnread)
                    <em>{{ $submissionsUnread }} belum dibaca</em>
                @endif
            </span>
        </a>
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
        <a class="stat-card" href="{{ route('admin.portfolios.index') }}">
            <i class="fa-solid fa-building-columns"></i>
            <span class="stat-value">{{ $portfoliosCount }}</span>
            <span class="stat-label">Karya Portofolio</span>
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
        <a class="stat-card" href="{{ route('admin.advantages.index') }}">
            <i class="fa-solid fa-scale-balanced"></i>
            <span class="stat-value">{{ $advantagesCount }}</span>
            <span class="stat-label">Untung &amp; Rugi</span>
        </a>
        <a class="stat-card" href="{{ route('admin.about.edit') }}">
            <i class="fa-regular fa-building"></i>
            <span class="stat-value">{{ $clientsCount }}</span>
            <span class="stat-label">Klien</span>
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
            <a class="btn btn-outline-red" href="{{ route('admin.portfolios.create') }}"><i class="fa-solid fa-plus"></i> Tambah Karya Portofolio</a>
            <a class="btn btn-outline-red" href="{{ route('admin.galleries.index') }}"><i class="fa-solid fa-upload"></i> Tambah Foto Galeri</a>
            <a class="btn btn-outline-red" href="{{ route('admin.services.index') }}"><i class="fa-solid fa-hammer"></i> Kelola Layanan</a>
            <a class="btn btn-outline-red" href="{{ route('home') }}" target="_blank"><i class="fa-solid fa-globe"></i> Lihat Situs</a>
        </div>
    </div>

    <div class="card">
        <h2>Panduan Singkat</h2>
        <p class="guide-intro">
            Urutannya sama dengan menu di sebelah kiri. Setiap menu punya tombol
            <em>Lihat Halaman</em> untuk membuka hasilnya di situs.
        </p>

        <ul class="guide-list">
            <li>
                <strong>Tentang CoreArsitek</strong> — seluruh isi halaman <em>Tentang</em> dalam satu tempat:
                judul, tagline, pengantar, visi, misi, lalu profil beserta foto, jabatan, kutipan, deskripsi,
                dan bidang keahlian. Misi dan bidang keahlian ditulis <strong>satu poin per baris</strong>.
                Di bagian bawah halaman ini ada pengelola <strong>Logo Klien</strong>; tombol
                <em>Duplikasi</em> pada tiap kartu menyalin klien beserta logonya supaya input berikutnya
                tinggal ganti nama. Bagian Klien di Beranda dan halaman Tentang otomatis disembunyikan
                selama belum ada logo yang diisi.
            </li>
            <li>
                <strong>Konten Situs</strong> — isian yang dipakai di banyak halaman sekaligus: logo dan
                gambar OG, judul hero cadangan, angka statistik, info kontak (alamat, telepon, email,
                nomor WhatsApp, jam operasional, tautan Google Maps), teks SEO, dan teks footer.
                Di sini juga ada <strong>Halaman Kontak &amp; Pengajuan</strong> untuk mengubah judul,
                pengantar, dan <strong>alamat email penerima pengajuan</strong>.
            </li>
            <li>
                <strong>Banner</strong> — enam halaman punya bannernya sendiri: Beranda, Portofolio,
                Layanan, Galeri, Tentang CoreArsitek, dan Kontak. Pilih halamannya lewat kolom
                <em>Tampil di Halaman</em>. Satu halaman boleh diisi lebih dari satu banner; kalau begitu
                tampilannya otomatis jadi slider mengikuti kolom Urutan. Kalau sebuah halaman belum punya
                banner aktif, gambar hero dari Konten Situs yang dipakai.
            </li>
            <li>
                <strong>Layanan</strong> — kartu layanan (ikon, judul, deskripsi) yang tampil di Beranda
                dan halaman <em>Layanan</em>. Judulnya juga otomatis jadi pilihan <em>Jenis Layanan</em>
                pada formulir pengajuan di halaman Kontak. Saat ini terisi {{ $servicesCount }} layanan.
            </li>
            <li>
                <strong>Keunggulan</strong> — daftar pada bagian “Apa yang Anda Dapatkan?” di Beranda,
                saat ini {{ $featuresCount }} item. Nama ikon memakai Font Awesome versi gratis
                (mis. <em>fa-ruler-combined</em>); ikon berbayar tidak akan muncul.
            </li>
            <li>
                <strong>Portofolio</strong> — karya lengkap beserta kategori, gaya, klien, lokasi, luas
                bangunan, dimensi lahan, dan banyak foto per karya. Tampil di halaman <em>Portofolio</em>,
                dan lima karya teratas ikut tampil di Beranda. Foto-foto tambahan sebuah karya menjadi
                halaman <em>flipbook</em> pada halaman detailnya. Centang <em>Unggulan</em> hanya sebagai
                penanda internal — sejak deretan “Karya Pilihan” dihapus, tanda ini tidak lagi mengubah
                tampilan situs.
            </li>
            <li>
                <strong>Galeri</strong> — foto lepas tanpa detail proyek, tampil tiga per baris memenuhi
                lebar halaman <em>Galeri</em>. Judul dan keterangan tiap foto muncul saat foto dibuka
                besar dalam modal.
            </li>
            <li>
                <strong>Untung &amp; Rugi</strong> — dua daftar berdampingan di Beranda: kerugian bila
                membangun tanpa jasa arsitek, dan alasan memilih CoreArsitek. Pilih daftarnya lewat kolom
                <em>Jenis</em>.
            </li>
            <li>
                <strong>Proses Kerja</strong> — tahapan kerja yang tampil berurutan di Beranda. Di layar
                ponsel tahapannya bisa digeser ke samping.
            </li>
            <li>
                <strong>Data Pengajuan</strong> — pengajuan pembuatan desain yang masuk dari formulir di
                halaman Kontak. Setiap pengajuan baru ditandai garis merah dan dihitung pada lencana di
                menu. Buka satu baris untuk melihat rincian proyek, menghubungi calon klien lewat WhatsApp
                atau email, mengubah statusnya (Baru, Sudah Dihubungi, Penawaran Dikirim, Deal, Batal),
                dan menulis catatan internal yang tidak terlihat oleh calon klien.
            </li>
            <li>
                <strong>Testimoni</strong> — ulasan klien beserta foto, nama, dan keterangannya. Hanya yang
                berstatus aktif yang tampil di Beranda.
            </li>
            <li>
                <strong>Profil</strong> — ubah nama, email, telepon, jabatan, bio, foto profil, dan
                password akun Anda sendiri.
            </li>
        </ul>

        <h3 class="guide-sub">Yang perlu diingat</h3>
        <ul class="guide-list">
            <li>
                <strong>Unggah gambar</strong> — format JPG, JPEG, atau PNG, maksimal
                {{ (int) (\App\Support\UploadHelper::MAX_UPLOAD_KB / 1024) }} MB per berkas. Setiap gambar
                otomatis dikompres ke WebP supaya halaman tetap ringan, jadi tidak perlu memperkecilnya
                sendiri lebih dulu.
            </li>
            <li>
                <strong>Kolom Urutan</strong> — angka kecil tampil lebih dulu. Kalau angkanya sama, data
                yang lebih dulu dibuat yang tampil lebih atas.
            </li>
            <li>
                <strong>Menyembunyikan tanpa menghapus</strong> — hilangkan centang <em>Aktif</em> bila
                sebuah data ingin disimpan tapi tidak ditampilkan di situs.
            </li>
            <li>
                <strong>Email pengajuan</strong> — pengajuan selalu tersimpan di menu Data Pengajuan,
                bahkan bila pengiriman emailnya gagal. Kalau gagal, penyebabnya ditampilkan pada halaman
                detail pengajuan dan pengaturan SMTP di berkas <em>.env</em> server perlu diperiksa.
            </li>
        </ul>
    </div>
@endsection