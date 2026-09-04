@extends('admin.layouts.app')

@section('title', 'Layanan')

@section('content')
    <h1 class="page-title">Layanan</h1>

    <div class="card">
        <h2>Tambah Layanan</h2>
        <form method="POST" action="{{ route('admin.services.store') }}" class="form-inline">
            @csrf
            <div class="field">
                <label>Judul</label>
                <input type="text" name="title" placeholder="JASA DESAIN RUMAH" required>
            </div>
            <div class="field">
                <label>Deskripsi</label>
                <input type="text" name="subtitle" placeholder="Deskripsi singkat layanan">
            </div>
            <div class="field">
                <label>Ikon (Font Awesome)</label>
                <input type="text" name="icon" placeholder="fa-house-chimney" value="fa-house-chimney">
            </div>
            <div class="field field-sm">
                <label>Urutan</label>
                <input type="number" name="sort_order" value="0">
            </div>
            <button type="submit" class="btn btn-gold"><i class="fa-solid fa-plus"></i> Tambah</button>
        </form>
    </div>

    @foreach ($services as $service)
        <div class="card card-row">
            <div class="card-row-icon"><i class="fa-solid {{ $service->icon }}"></i></div>
            <form method="POST" action="{{ route('admin.services.update', $service) }}" class="form-inline form-grow">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Judul</label>
                    <input type="text" name="title" value="{{ $service->title }}" required>
                </div>
                <div class="field">
                    <label>Deskripsi</label>
                    <input type="text" name="subtitle" value="{{ $service->subtitle }}">
                </div>
                <div class="field">
                    <label>Ikon</label>
                    <input type="text" name="icon" value="{{ $service->icon }}">
                </div>
                <div class="field field-sm">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="{{ $service->sort_order }}">
                </div>
                <button type="submit" class="btn btn-ghost"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </form>
            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Hapus layanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
            </form>
        </div>
    @endforeach
@endsection