@extends('admin.layouts.app')

@section('title', 'Testimoni')

@section('content')
    <h1 class="page-title">
        Testimoni Klien
        <small>Tampil pada bagian testimoni di halaman depan.</small>
    </h1>

    <div id="baris-testimoni" class="card">
        <h2>Tambah Testimoni</h2>
        <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-inline">
                <div class="field">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Bapak Andi Prasetya" required>
                </div>
                <div class="field">
                    <label>Keterangan</label>
                    <input type="text" name="role" value="{{ old('role') }}" placeholder="Pemilik Rumah, Jakarta Selatan">
                </div>
            </div>
            <div class="field">
                <label>Testimoni</label>
                <textarea name="quote" rows="3" placeholder="Tulis testimoni klien di sini." required>{{ old('quote') }}</textarea>
            </div>
            <div class="form-inline">
                <div class="field form-grow">
                    <label>Foto Klien</label>
                    <input type="file" name="avatar" accept=".jpg,.jpeg,.png">
                    <small class="hint">Opsional; kalau kosong, inisial nama dipakai sebagai avatar. {{ \App\Support\UploadHelper::hint() }}</small>
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

    @forelse ($testimonials as $testimonial)
        <div id="baris-testimoni-{{ $testimonial->id }}" class="card">
            <div class="card-row">
                @if ($testimonial->avatar)
                    <div class="card-row-avatar">
                        <img src="{{ asset($testimonial->avatar) }}" alt="{{ $testimonial->name }}">
                    </div>
                @else
                    <div class="card-row-icon">{{ \Illuminate\Support\Str::of($testimonial->name)->explode(' ')->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('') }}</div>
                @endif
                <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data" class="form-grow">
                    @csrf
                    @method('PUT')
                    <div class="form-inline">
                        <div class="field">
                            <label>Nama</label>
                            <input type="text" name="name" value="{{ $testimonial->name }}" required>
                        </div>
                        <div class="field">
                            <label>Keterangan</label>
                            <input type="text" name="role" value="{{ $testimonial->role }}">
                        </div>
                        @include('admin.partials.field-en', ['nama' => 'role_en', 'label' => 'Keterangan', 'nilai' => $testimonial->role_en])
                    </div>
                    <div class="field">
                        <label>Testimoni</label>
                        <textarea name="quote" rows="3" required>{{ $testimonial->quote }}</textarea>
                    </div>
                    @include('admin.partials.field-en', ['nama' => 'quote_en', 'label' => 'Testimoni', 'nilai' => $testimonial->quote_en, 'tipe' => 'textarea', 'rows' => 3])
                    <div class="form-inline">
                        <div class="field form-grow">
                            <label>Ganti Foto</label>
                            <input type="file" name="avatar" accept=".jpg,.jpeg,.png">
                        </div>
                        <div class="field field-sm">
                            <label>Urutan</label>
                            <input type="number" name="sort_order" value="{{ $testimonial->sort_order }}">
                        </div>
                        <div class="field field-sm">
                            <label>Tampilkan</label>
                            <label class="checkbox"><input type="checkbox" name="is_active" value="1" @checked($testimonial->is_active)> Aktif</label>
                        </div>
                        <button type="submit" class="btn btn-ghost"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Hapus testimoni ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <p class="muted">Belum ada testimoni. Bagian testimoni di halaman depan disembunyikan selama masih kosong.</p>
        </div>
    @endforelse
@endsection
