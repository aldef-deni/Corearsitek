@extends('admin.layouts.app')

@section('title', 'Proses Kerja')

@section('content')
    <h1 class="page-title">
        Proses Kerja
        <small>Tahapan kerja yang tampil berurutan di halaman depan.</small>
    </h1>

    <div class="card">
        <h2>Tambah Tahap</h2>
        <form method="POST" action="{{ route('admin.process-steps.store') }}">
            @csrf
            <div class="form-inline">
                <div class="field field-sm">
                    <label>Ikon</label>
                    <input type="text" name="icon" value="{{ old('icon', 'fa-circle-check') }}" required>
                </div>
                <div class="field">
                    <label>Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Hubungi Kami" required>
                </div>
                <div class="field form-grow">
                    <label>Keterangan</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="Penjelasan singkat tahap ini">
                </div>
                <div class="field field-sm">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
                </div>
                <button type="submit" class="btn btn-red"><i class="fa-solid fa-plus"></i> Tambah</button>
            </div>
            <small class="hint">Nama ikon Font Awesome, contoh: <code>fa-comments</code>, <code>fa-file-invoice</code>, <code>fa-compass-drafting</code>, <code>fa-box-open</code>.</small>
        </form>
    </div>

    @forelse ($steps as $step)
        <div class="card card-row">
            <div class="card-row-icon"><i class="fa-solid {{ $step->icon }}"></i></div>
            <form method="POST" action="{{ route('admin.process-steps.update', $step) }}" class="form-inline form-grow">
                @csrf
                @method('PUT')
                <div class="field field-sm">
                    <label>Ikon</label>
                    <input type="text" name="icon" value="{{ $step->icon }}" required>
                </div>
                <div class="field">
                    <label>Judul</label>
                    <input type="text" name="title" value="{{ $step->title }}" required>
                </div>
                <div class="field form-grow">
                    <label>Keterangan</label>
                    <input type="text" name="description" value="{{ $step->description }}">
                </div>
                <div class="field field-sm">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="{{ $step->sort_order }}">
                </div>
                <button type="submit" class="btn btn-ghost"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </form>
            <form method="POST" action="{{ route('admin.process-steps.destroy', $step) }}" onsubmit="return confirm('Hapus tahap ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
            </form>
        </div>
    @empty
        <div class="card">
            <p class="muted">Belum ada tahap kerja. Bagian ini disembunyikan di halaman depan selama masih kosong.</p>
        </div>
    @endforelse
@endsection
