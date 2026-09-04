@extends('admin.layouts.app')

@section('title', 'Banner')

@section('content')
    <h1 class="page-title">
        Banner Hero
        <small>Tampil sebagai slider di bagian paling atas halaman depan.</small>
    </h1>

    <div class="card">
        <h2>Tambah Banner</h2>
        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-inline">
                <div class="field">
                    <label>Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="JASA DESAIN RUMAH MEWAH">
                </div>
                <div class="field">
                    <label>Subjudul</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="Hunian Aman, Nyaman dan Elegan">
                </div>
            </div>
            <div class="form-inline">
                <div class="field">
                    <label>Teks Badge</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text') }}" placeholder="LUXURY LIFESTYLE AWARDS WINNER 2023">
                </div>
                <div class="field">
                    <label>Teks Tombol</label>
                    <input type="text" name="button_text" value="{{ old('button_text') }}" placeholder="KONSULTASI GRATIS">
                </div>
                <div class="field">
                    <label>Link Tombol</label>
                    <input type="text" name="button_url" value="{{ old('button_url') }}" placeholder="/portofolio atau #kontak">
                </div>
            </div>
            <div class="form-inline">
                <div class="field form-grow">
                    <label>Gambar Banner</label>
                    <input type="file" name="image" accept="image/*" required>
                    <small class="hint">Disarankan 1920&times;1080 px atau lebih besar, format JPG/PNG/WebP, maks 5MB.</small>
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

    @forelse ($banners as $banner)
        <div class="card">
            <div class="card-row">
                <div class="card-row-thumb card-row-thumb-wide">
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}">
                </div>
                <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data" class="form-grow">
                    @csrf
                    @method('PUT')
                    <div class="form-inline">
                        <div class="field">
                            <label>Judul</label>
                            <input type="text" name="title" value="{{ $banner->title }}">
                        </div>
                        <div class="field">
                            <label>Subjudul</label>
                            <input type="text" name="subtitle" value="{{ $banner->subtitle }}">
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="field">
                            <label>Teks Badge</label>
                            <input type="text" name="badge_text" value="{{ $banner->badge_text }}">
                        </div>
                        <div class="field">
                            <label>Teks Tombol</label>
                            <input type="text" name="button_text" value="{{ $banner->button_text }}">
                        </div>
                        <div class="field">
                            <label>Link Tombol</label>
                            <input type="text" name="button_url" value="{{ $banner->button_url }}">
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="field form-grow">
                            <label>Ganti Gambar</label>
                            <input type="file" name="image" accept="image/*">
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
            <p class="muted">Belum ada banner. Selama kosong, halaman depan memakai Gambar Hero dari menu Konten Situs.</p>
        </div>
    @endforelse
@endsection
