@extends('admin.layouts.app')

@section('title', 'Tentang CoreArsitek')

@section('content')
    <h1 class="page-title">
        Tentang CoreArsitek
        <small>Seluruh isi halaman Tentang: pengantar, visi &amp; misi, profil, dan logo klien.</small>
    </h1>

    <div class="toolbar">
        <a href="{{ route('about') }}" target="_blank" class="btn btn-outline-red">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Halaman
        </a>
    </div>

    <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <h2>Pengantar</h2>

            <div class="field">
                <label for="about_title">Judul</label>
                <input type="text" name="about_title" id="about_title"
                       value="{{ old('about_title', $contents['about_title'] ?? '') }}"
                       placeholder="TENTANG COREARSITEK">
            </div>

            <div class="field">
                <label for="about_tagline">Tagline</label>
                <input type="text" name="about_tagline" id="about_tagline"
                       value="{{ old('about_tagline', $contents['about_tagline'] ?? '') }}"
                       placeholder="Kalimat singkat yang mewakili CoreArsitek">
                <small class="hint">Tampil sebagai kutipan besar di bawah pengantar.</small>
            </div>

            <div class="field">
                <label for="about_text">Pengantar</label>
                <textarea name="about_text" id="about_text" rows="5"
                          placeholder="Ceritakan siapa CoreArsitek dalam beberapa kalimat.">{{ old('about_text', $contents['about_text'] ?? '') }}</textarea>
            </div>
        </div>

        <div class="card">
            <h2>Visi &amp; Misi</h2>

            <div class="field">
                <label for="about_vision">Visi</label>
                <textarea name="about_vision" id="about_vision" rows="4">{{ old('about_vision', $contents['about_vision'] ?? '') }}</textarea>
            </div>

            <div class="field">
                <label for="about_mission">Misi</label>
                <textarea name="about_mission" id="about_mission" rows="6">{{ old('about_mission', $contents['about_mission'] ?? '') }}</textarea>
                <small class="hint">Tulis <strong>satu poin per baris</strong>. Tiap baris tampil sebagai butir bernomor di halaman.</small>
            </div>
        </div>

        <div class="card">
            <h2>Profil</h2>

            <div class="form-inline">
                @if (!empty($contents['about_profile_image']))
                    <div class="card-row-avatar card-row-avatar-lg">
                        <img src="{{ asset($contents['about_profile_image']) }}" alt="Foto profil">
                    </div>
                @endif
                <div class="field form-grow">
                    <label for="about_profile_image">
                        {{ !empty($contents['about_profile_image']) ? 'Ganti Foto Profil' : 'Foto Profil' }}
                    </label>
                    <input type="file" name="about_profile_image" id="about_profile_image" accept=".jpg,.jpeg,.png">
                    <small class="hint">{{ \App\Support\UploadHelper::hint() }} Foto potret memberi hasil terbaik.</small>
                </div>
            </div>

            <div class="form-inline">
                <div class="field">
                    <label for="about_profile_name">Nama</label>
                    <input type="text" name="about_profile_name" id="about_profile_name"
                           value="{{ old('about_profile_name', $contents['about_profile_name'] ?? '') }}"
                           placeholder="mis. Ade Zulham">
                </div>
                <div class="field">
                    <label for="about_profile_role">Jabatan</label>
                    <input type="text" name="about_profile_role" id="about_profile_role"
                           value="{{ old('about_profile_role', $contents['about_profile_role'] ?? '') }}"
                           placeholder="Principal Architect">
                </div>
            </div>

            <div class="field">
                <label for="about_profile_quote">Kutipan</label>
                <textarea name="about_profile_quote" id="about_profile_quote" rows="3"
                          placeholder="Satu kalimat yang mewakili cara kerja atau keyakinan Anda.">{{ old('about_profile_quote', $contents['about_profile_quote'] ?? '') }}</textarea>
            </div>

            <div class="field">
                <label for="about_profile_bio">Deskripsi</label>
                <textarea name="about_profile_bio" id="about_profile_bio" rows="7"
                          placeholder="Latar belakang, pengalaman, dan pendekatan kerja.">{{ old('about_profile_bio', $contents['about_profile_bio'] ?? '') }}</textarea>
            </div>

            <div class="field">
                <label for="about_profile_skills">Bidang Keahlian</label>
                <textarea name="about_profile_skills" id="about_profile_skills" rows="6">{{ old('about_profile_skills', $contents['about_profile_skills'] ?? '') }}</textarea>
                <small class="hint">Tulis <strong>satu keahlian per baris</strong>. Tiap baris tampil sebagai label di halaman.</small>
            </div>
        </div>

        <button type="submit" class="btn btn-red"><i class="fa-solid fa-floppy-disk"></i> SIMPAN HALAMAN TENTANG</button>
    </form>

    @if (!empty($contents['about_profile_image']))
        <form method="POST" action="{{ route('admin.about.image.destroy') }}"
              onsubmit="return confirm('Hapus foto profil?')" style="margin-top: 14px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-ghost"><i class="fa-solid fa-trash"></i> Hapus Foto Profil</button>
        </form>
    @endif

    {{-- ---------- Logo klien ---------- --}}
    <h2 class="group-title" id="baris-klien" style="margin-top: 34px;">
        <i class="fa-solid fa-handshake"></i> Logo Klien
        <span class="group-count">{{ $clients->count() }} klien</span>
    </h2>

    <div class="card">
        <h2>Tambah Klien</h2>
        <form method="POST" action="{{ route('admin.clients.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-inline">
                <div class="field">
                    <label>Nama Klien</label>
                    <input type="text" name="name" placeholder="mis. PT Contoh Sejahtera" required>
                </div>
                <div class="field">
                    <label>Tautan (opsional)</label>
                    <input type="text" name="url" placeholder="https://...">
                </div>
            </div>
            <div class="form-inline">
                <div class="field form-grow">
                    <label>Logo</label>
                    <input type="file" name="logo" accept=".jpg,.jpeg,.png" required>
                    <small class="hint">Logo berlatar putih atau transparan memberi hasil terbaik. {{ \App\Support\UploadHelper::hint() }}</small>
                </div>
                <div class="field field-sm">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="0">
                </div>
                <div class="field field-sm">
                    <label>Tampilkan</label>
                    <label class="checkbox"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
                </div>
                <button type="submit" class="btn btn-red"><i class="fa-solid fa-plus"></i> Tambah</button>
            </div>
        </form>
    </div>

    @forelse ($clients as $client)
        <div class="card card-row" id="baris-klien-{{ $client->id }}">
            <div class="card-row-thumb card-row-logo">
                <img src="{{ asset($client->logo) }}" alt="{{ $client->name }}">
            </div>
            <form method="POST" action="{{ route('admin.clients.update', $client) }}"
                  enctype="multipart/form-data" class="form-grow">
                @csrf
                @method('PUT')
                <div class="form-inline">
                    <div class="field">
                        <label>Nama Klien</label>
                        <input type="text" name="name" value="{{ $client->name }}" required>
                    </div>
                    <div class="field">
                        <label>Tautan</label>
                        <input type="text" name="url" value="{{ $client->url }}">
                    </div>
                </div>
                <div class="form-inline">
                    <div class="field form-grow">
                        <label>Ganti Logo</label>
                        <input type="file" name="logo" accept=".jpg,.jpeg,.png">
                    </div>
                    <div class="field field-sm">
                        <label>Urutan</label>
                        <input type="number" name="sort_order" value="{{ $client->sort_order }}">
                    </div>
                    <div class="field field-sm">
                        <label>Tampilkan</label>
                        <label class="checkbox"><input type="checkbox" name="is_active" value="1" @checked($client->is_active)> Aktif</label>
                    </div>
                    <button type="submit" class="btn btn-ghost"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                </div>
            </form>
            <div class="card-row-actions">
                {{-- Menyalin klien ini beserta logonya, tinggal ganti nama. --}}
                <form method="POST" action="{{ route('admin.clients.duplicate', $client) }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost" title="Duplikasi klien ini">
                        <i class="fa-regular fa-clone"></i> Duplikasi
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}"
                      onsubmit="return confirm('Hapus klien ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <p class="muted">Belum ada logo klien. Bagian Klien di halaman Tentang dan Beranda disembunyikan selama masih kosong.</p>
        </div>
    @endforelse
@endsection
