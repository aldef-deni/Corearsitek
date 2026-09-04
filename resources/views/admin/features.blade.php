@extends('admin.layouts.app')

@section('title', 'Keunggulan')

@section('content')
    <h1 class="page-title">Keunggulan <small>(Apa yang Anda Dapatkan?)</small></h1>

    <div id="baris-fitur" class="card">
        <h2>Tambah Keunggulan</h2>
        <form method="POST" action="{{ route('admin.features.store') }}" class="form-inline">
            @csrf
            <div class="field">
                <label>Label</label>
                <input type="text" name="label" placeholder="Simbol &amp; Status Elit" required>
            </div>
            <div class="field">
                <label>Ikon (Font Awesome)</label>
                <input type="text" name="icon" placeholder="fa-crown" value="fa-check">
            </div>
            <div class="field field-sm">
                <label>Urutan</label>
                <input type="number" name="sort_order" value="0">
            </div>
            <button type="submit" class="btn btn-red"><i class="fa-solid fa-plus"></i> Tambah</button>
        </form>
    </div>

    @foreach ($features as $feature)
        <div id="baris-fitur-{{ $feature->id }}" class="card card-row">
            <div class="card-row-icon"><i class="fa-solid {{ $feature->icon }}"></i></div>
            <form method="POST" action="{{ route('admin.features.update', $feature) }}" class="form-inline form-grow">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Label</label>
                    <input type="text" name="label" value="{{ $feature->label }}" required>
                </div>
                <div class="field">
                    <label>Ikon</label>
                    <input type="text" name="icon" value="{{ $feature->icon }}">
                </div>
                <div class="field field-sm">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="{{ $feature->sort_order }}">
                </div>
                <button type="submit" class="btn btn-ghost"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </form>
            <form method="POST" action="{{ route('admin.features.destroy', $feature) }}" onsubmit="return confirm('Hapus keunggulan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
            </form>
        </div>
    @endforeach
@endsection