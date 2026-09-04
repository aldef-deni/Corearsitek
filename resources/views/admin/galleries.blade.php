@extends('admin.layouts.app')

@section('title', 'Galeri')

@section('content')
    <h1 class="page-title">Galeri / Portofolio</h1>

    <div class="card">
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
        <div class="card card-row">
            <div class="card-row-thumb">
                <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->title }}">
            </div>
            <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data" class="form-inline form-grow">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Judul</label>
                    <input type="text" name="title" value="{{ $gallery->title }}" required>
                </div>
                <div class="field">
                    <label>Deskripsi</label>
                    <input type="text" name="description" value="{{ $gallery->description }}">
                </div>
                <div class="field">
                    <label>Ganti Foto</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png">
                </div>
                <div class="field field-sm">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="{{ $gallery->sort_order }}">
                </div>
                <button type="submit" class="btn btn-ghost"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </form>
            <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" onsubmit="return confirm('Hapus foto ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
            </form>
        </div>
    @endforeach
@endsection