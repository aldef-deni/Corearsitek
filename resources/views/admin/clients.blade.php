@extends('admin.layouts.app')

@section('title', 'Klien')

@section('content')
    <h1 class="page-title">
        Klien Kami
        <small>Logo klien yang tampil di halaman depan. Logo berlatar transparan atau putih memberi hasil terbaik.</small>
    </h1>

    <div class="card">
        <h2>Tambah Klien</h2>
        <form method="POST" action="{{ route('admin.clients.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-inline">
                <div class="field">
                    <label>Nama Klien</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="mis. Bank Mandiri" required>
                </div>
                <div class="field">
                    <label>Tautan (opsional)</label>
                    <input type="text" name="url" value="{{ old('url') }}" placeholder="https://...">
                </div>
            </div>
            <div class="form-inline">
                <div class="field form-grow">
                    <label>Logo</label>
                    <input type="file" name="logo" accept=".jpg,.jpeg,.png" required>
                    <small class="hint">{{ \App\Support\UploadHelper::hint() }}</small>
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

    @forelse ($clients as $client)
        <div class="card card-row">
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
            <form method="POST" action="{{ route('admin.clients.destroy', $client) }}"
                  onsubmit="return confirm('Hapus klien ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
            </form>
        </div>
    @empty
        <div class="card">
            <p class="muted">Belum ada klien. Bagian Klien Kami di halaman depan disembunyikan selama masih kosong.</p>
        </div>
    @endforelse
@endsection
