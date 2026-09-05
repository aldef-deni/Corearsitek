@extends('admin.layouts.app')

@section('title', 'Benefit CoreArsitek')

@section('content')
    <h1 class="page-title">
        Benefit CoreArsitek
        <small>Dua daftar berdampingan di halaman depan: kerugian bila tanpa jasa arsitek, dan alasan memilih CoreArsitek.</small>
    </h1>

    <div id="baris-poin" class="card">
        <h2>Tambah Poin</h2>
        <form method="POST" action="{{ route('admin.benefit.store') }}">
            @csrf
            <div class="form-inline">
                <div class="field">
                    <label>Masuk Daftar</label>
                    <select name="type" required>
                        @foreach (\App\Models\Advantage::TYPES as $key => $label)
                            <option value="{{ $key }}" @selected(old('type') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field form-grow">
                    <label>Isi Poin</label>
                    <input type="text" name="text" value="{{ old('text') }}"
                           placeholder="mis. Tata ruang semrawut" required>
                </div>
                <div class="field field-sm">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
                </div>
                <button type="submit" class="btn btn-red"><i class="fa-solid fa-plus"></i> Tambah</button>
            </div>
        </form>
    </div>

    @foreach (\App\Models\Advantage::TYPES as $key => $label)
        @php $daftar = $advantages[$key] ?? collect(); @endphp

        <h2 class="group-title">
            <i class="fa-solid {{ $key === 'rugi' ? 'fa-circle-xmark' : 'fa-circle-check' }}"></i> {{ $label }}
            <span class="group-count">{{ $daftar->count() }} poin</span>
        </h2>

        @forelse ($daftar as $poin)
            <div id="baris-poin-{{ $poin->id }}" class="card card-row">
                <form method="POST" action="{{ route('admin.benefit.update', $poin) }}" class="form-inline form-grow">
                    @csrf
                    @method('PUT')
                    <div class="field">
                        <label>Masuk Daftar</label>
                        <select name="type" required>
                            @foreach (\App\Models\Advantage::TYPES as $k => $l)
                                <option value="{{ $k }}" @selected($poin->type === $k)>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field form-grow">
                        <label>Isi Poin</label>
                        <input type="text" name="text" value="{{ $poin->text }}" required>
                    </div>
                        @include('admin.partials.field-en', ['nama' => 'text_en', 'label' => 'Teks', 'nilai' => $poin->text_en])
                    <div class="field field-sm">
                        <label>Urutan</label>
                        <input type="number" name="sort_order" value="{{ $poin->sort_order }}">
                    </div>
                    <button type="submit" class="btn btn-red btn-flash"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                </form>
                <form method="POST" action="{{ route('admin.benefit.destroy', $poin) }}"
                      onsubmit="return confirm('Hapus poin ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        @empty
            <div class="card">
                <p class="muted">Belum ada poin. Bagian ini disembunyikan di halaman depan selama kedua daftar masih kosong.</p>
            </div>
        @endforelse
    @endforeach
@endsection
