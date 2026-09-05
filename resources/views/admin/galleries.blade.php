@extends('admin.layouts.app')

@section('title', 'Galeri')

@section('content')
    <h1 class="page-title">
        Galeri
        <small>Foto di sini tampil pada halaman Galeri di situs. Untuk karya lengkap dengan spesifikasi proyek, pakai menu Portofolio.</small>
    </h1>

    <div class="toolbar">
        <a href="{{ route('gallery') }}" target="_blank" class="btn btn-outline-red">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Halaman Galeri
        </a>
    </div>

    <div id="baris-galeri" class="card">
        <h2>Tambah Foto</h2>
        <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data" class="form-inline">
            @csrf
            <div class="field">
                <label>Judul</label>
                <input type="text" name="title" placeholder="Rumah Klasik Modern Jakarta" required>
            </div>
            <div class="field">
                <label>Deskripsi</label>
                <input type="text" name="description" placeholder="Deskripsi singkat">
            </div>
            <div class="field">
                <label>Foto</label>
                <input type="file" name="image" accept=".jpg,.jpeg,.png" required>
                <small class="hint">{{ \App\Support\UploadHelper::hint() }}</small>
            </div>
            <div class="field field-sm">
                <label>Urutan</label>
                <input type="number" name="sort_order" value="0">
            </div>
            <button type="submit" class="btn btn-red"><i class="fa-solid fa-upload"></i> Tambah</button>
        </form>
    </div>

    @foreach ($galleries as $gallery)
        <div id="baris-galeri-{{ $gallery->id }}" class="card card-row">
            <div class="card-row-thumb">
                <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->title }}">
            </div>
            <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data"
                  class="form-inline form-grow" data-row="{{ $gallery->id }}">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Judul</label>
                    <input type="text" name="title" value="{{ $gallery->title }}" required>
                </div>
                    @include('admin.partials.field-en', ['nama' => 'title_en', 'label' => 'Judul', 'nilai' => $gallery->title_en])
                <div class="field">
                    <label>Deskripsi</label>
                    <input type="text" name="description" value="{{ $gallery->description }}">
                </div>
                    @include('admin.partials.field-en', ['nama' => 'description_en', 'label' => 'Deskripsi', 'nilai' => $gallery->description_en])
                <div class="field">
                    <label>Ganti Foto</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png">
                </div>
                <div class="field field-sm">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="{{ $gallery->sort_order }}">
                </div>
                <button type="submit" class="btn btn-red btn-flash"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </form>
            <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" onsubmit="return confirm('Hapus foto ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
            </form>
        </div>
    @endforeach

    @if ($galleries->count())
        {{-- Mengumpulkan isian seluruh baris di atas lalu mengirimnya sekaligus.
             Penggantian foto tidak ikut; itu tetap lewat tombol Simpan barisnya. --}}
        <div class="save-all">
            <button type="button" class="btn btn-red btn-flash"
                    data-save-all="{{ route('admin.galleries.saveAll') }}" data-token="{{ csrf_token() }}">
                <i class="fa-solid fa-floppy-disk"></i> SIMPAN SEMUA
            </button>
        </div>
    @endif
@endsection
