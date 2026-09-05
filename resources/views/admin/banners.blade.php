@extends('admin.layouts.app')

@section('title', 'Banner')

@section('content')
    <h1 class="page-title">
        Banner
        <small>Tiap halaman punya bannernya sendiri. Kalau satu halaman diisi lebih dari satu banner, tampilannya otomatis jadi slider.</small>
    </h1>

    <div id="baris-banner" class="card">
        <h2>Tambah Banner</h2>
        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-inline">
                <div class="field">
                    <label>Tampil di Halaman</label>
                    <select name="page" required>
                        @foreach (\App\Models\Banner::PAGES as $key => $label)
                            <option value="{{ $key }}" @selected(old('page') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Boleh dikosongkan">
                </div>
                <div class="field">
                    <label>Subjudul</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="Boleh dikosongkan">
                </div>
            </div>
            <div class="form-inline">
                <div class="field">
                    <label>Teks Badge</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text') }}" placeholder="mis. LUXURY LIFESTYLE AWARDS WINNER 2023">
                </div>
                <div class="field">
                    <label>Teks Tombol</label>
                    <input type="text" name="button_text" value="{{ old('button_text') }}" placeholder="Kosongkan bila tak perlu tombol">
                </div>
                <div class="field">
                    <label>Link Tombol</label>
                    <input type="text" name="button_url" value="{{ old('button_url') }}" placeholder="/portofolio atau #kontak">
                </div>
            </div>
            <div class="form-inline">
                <div class="field form-grow">
                    <label>Gambar Banner</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png" required>
                    <small class="hint">Disarankan 1920&times;1080 px untuk Beranda dan 1920&times;640 px untuk halaman lain. {{ \App\Support\UploadHelper::hint() }}</small>
                </div>
                <div class="field field-sm">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="field field-sm">
                    <label>Tampilkan</label>
                    <label class="checkbox"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
                </div>
                <button type="submit" class="btn btn-red"><i class="fa-solid fa-plus"></i> Tambah</button>
            </div>
        </form>
    </div>

    @foreach (\App\Models\Banner::PAGES as $key => $label)
        @php $daftar = $banners[$key] ?? collect(); @endphp

        <h2 class="group-title">
            <i class="fa-solid fa-panorama"></i> {{ $label }}
            <span class="group-count">{{ $daftar->count() }} banner</span>
        </h2>

        @forelse ($daftar as $banner)
            <div id="baris-banner-{{ $banner->id }}" class="card">
                <div class="card-row">
                    <div class="card-row-thumb card-row-thumb-wide">
                        <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}">
                    </div>
                    <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data" class="form-grow">
                        @csrf
                        @method('PUT')
                        <div class="form-inline">
                            <div class="field">
                                <label>Tampil di Halaman</label>
                                <select name="page" required>
                                    @foreach (\App\Models\Banner::PAGES as $k => $l)
                                        <option value="{{ $k }}" @selected($banner->page === $k)>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Judul</label>
                                <input type="text" name="title" value="{{ $banner->title }}">
                            </div>
                            @include('admin.partials.field-en', ['nama' => 'title_en', 'label' => 'Judul', 'nilai' => $banner->title_en])
                            <div class="field">
                                <label>Subjudul</label>
                                <input type="text" name="subtitle" value="{{ $banner->subtitle }}">
                            </div>
                            @include('admin.partials.field-en', ['nama' => 'subtitle_en', 'label' => 'Subjudul', 'nilai' => $banner->subtitle_en])
                        </div>
                        <div class="form-inline">
                            <div class="field">
                                <label>Teks Badge</label>
                                <input type="text" name="badge_text" value="{{ $banner->badge_text }}">
                            </div>
                            @include('admin.partials.field-en', ['nama' => 'badge_text_en', 'label' => 'Teks Badge', 'nilai' => $banner->badge_text_en])
                            <div class="field">
                                <label>Teks Tombol</label>
                                <input type="text" name="button_text" value="{{ $banner->button_text }}">
                            </div>
                            @include('admin.partials.field-en', ['nama' => 'button_text_en', 'label' => 'Teks Tombol', 'nilai' => $banner->button_text_en])
                            <div class="field">
                                <label>Link Tombol</label>
                                <input type="text" name="button_url" value="{{ $banner->button_url }}">
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="field form-grow">
                                <label>Ganti Gambar</label>
                                <input type="file" name="image" accept=".jpg,.jpeg,.png">
                            </div>
                            <div class="field field-sm">
                                <label>Urutan</label>
                                <input type="number" name="sort_order" value="{{ $banner->sort_order }}">
                            </div>
                            <div class="field field-sm">
                                <label>Tampilkan</label>
                                <label class="checkbox"><input type="checkbox" name="is_active" value="1" @checked($banner->is_active)> Aktif</label>
                            </div>
                            <button type="submit" class="btn btn-ghost"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Hapus banner ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>
        @empty
            <div class="card">
                <p class="muted">
                    Belum ada banner untuk halaman {{ $label }}.
                    @if ($key === 'home')
                        Selama kosong, Beranda memakai Gambar Hero dari menu Konten Situs.
                    @else
                        Selama kosong, halaman ini memakai Gambar Hero dari menu Konten Situs beserta judul bawaannya.
                    @endif
                </p>
            </div>
        @endforelse
    @endforeach
@endsection
