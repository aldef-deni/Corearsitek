@extends('admin.layouts.app')

@php $baru = ! $portfolio->exists; @endphp

@section('title', $baru ? 'Tambah Karya' : 'Ubah Karya')

@section('content')
    <h1 class="page-title">
        {{ $baru ? 'Tambah Karya' : 'Ubah Karya' }}
        <small>{{ $baru ? 'Isi keterangan karya, lalu unggah foto tambahannya setelah tersimpan.' : $portfolio->title }}</small>
    </h1>

    <div class="toolbar">
        <a href="{{ route('admin.portfolios.index') }}" class="btn btn-ghost">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        @unless ($baru)
            <a href="{{ route('portfolio.show', $portfolio) }}" target="_blank" class="btn btn-outline-red">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat di Situs
            </a>
        @endunless
    </div>

    <form method="POST"
          action="{{ $baru ? route('admin.portfolios.store') : route('admin.portfolios.update', $portfolio) }}"
          enctype="multipart/form-data">
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <div class="card">
            <h2>Keterangan Karya</h2>

            <div class="field">
                <label for="title">Judul <span class="req">*</span></label>
                <input type="text" name="title" id="title" required
                       value="{{ old('title', $portfolio->title) }}"
                       placeholder="Desain Rumah Modern 2 Lantai Bapak AS di Bandung">
                <small class="hint">Tulis apa adanya seperti contoh; judul ini yang tampil di kartu portofolio.</small>
            </div>

            <div class="form-inline">
                <div class="field">
                    <label for="category">Kategori <span class="req">*</span></label>
                    <select name="category" id="category" required>
                        @foreach (\App\Models\Portfolio::CATEGORIES as $key => $label)
                            <option value="{{ $key }}" @selected(old('category', $portfolio->category) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="style">Gaya Desain</label>
                    <input type="text" name="style" id="style" list="daftar-gaya"
                           value="{{ old('style', $portfolio->style) }}" placeholder="Modern">
                    <datalist id="daftar-gaya">
                        @foreach (\App\Models\Portfolio::STYLE_SUGGESTIONS as $gaya)
                            <option value="{{ $gaya }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="field">
                    <label for="project_date">Tanggal Karya</label>
                    <input type="date" name="project_date" id="project_date"
                           value="{{ old('project_date', $portfolio->project_date?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="form-inline">
                <div class="field">
                    <label for="client">Klien</label>
                    <input type="text" name="client" id="client"
                           value="{{ old('client', $portfolio->client) }}" placeholder="Bapak AS">
                </div>
                <div class="field">
                    <label for="location">Lokasi</label>
                    <input type="text" name="location" id="location"
                           value="{{ old('location', $portfolio->location) }}" placeholder="Bandung, Jawa Barat">
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Spesifikasi</h2>
            <div class="form-inline">
                <div class="field field-sm">
                    <label for="floors">Jumlah Lantai</label>
                    <input type="number" name="floors" id="floors" min="1" max="100"
                           value="{{ old('floors', $portfolio->floors) }}" placeholder="2">
                </div>
                <div class="field">
                    <label for="building_area">Luas Bangunan (m&sup2;)</label>
                    <input type="number" name="building_area" id="building_area" step="0.01" min="0"
                           value="{{ old('building_area', $portfolio->building_area) }}" placeholder="305">
                </div>
                <div class="field">
                    <label for="land_width">Lebar Lahan (m)</label>
                    <input type="number" name="land_width" id="land_width" step="0.01" min="0"
                           value="{{ old('land_width', $portfolio->land_width) }}" placeholder="18.9">
                </div>
                <div class="field">
                    <label for="land_length">Panjang Lahan (m)</label>
                    <input type="number" name="land_length" id="land_length" step="0.01" min="0"
                           value="{{ old('land_length', $portfolio->land_length) }}" placeholder="41">
                </div>
            </div>
            <small class="hint">Boleh dikosongkan. Yang kosong tidak akan tampil di halaman portofolio.</small>

            <div class="field">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="5"
                          placeholder="Ceritakan singkat konsep dan keunikan karya ini.">{{ old('description', $portfolio->description) }}</textarea>
            </div>
        </div>

        <div class="card">
            <h2>Foto Utama</h2>
            <div class="form-inline">
                @if ($portfolio->cover_image)
                    <div class="card-row-thumb card-row-thumb-wide">
                        <img src="{{ asset($portfolio->cover_image) }}" alt="{{ $portfolio->title }}">
                    </div>
                @endif
                <div class="field form-grow">
                    <label for="cover_image">{{ $portfolio->cover_image ? 'Ganti Foto Utama' : 'Foto Utama' }} @if ($baru) <span class="req">*</span> @endif</label>
                    <input type="file" name="cover_image" id="cover_image" accept=".jpg,.jpeg,.png" @if ($baru) required @endif>
                    <small class="hint">{{ \App\Support\UploadHelper::hint() }}</small>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Pengaturan Tampil</h2>
            <div class="form-inline">
                <div class="field field-sm">
                    <label for="sort_order">Urutan</label>
                    <input type="number" name="sort_order" id="sort_order"
                           value="{{ old('sort_order', $portfolio->sort_order ?? 0) }}">
                </div>
                <div class="field">
                    <label>Status</label>
                    <label class="checkbox">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $portfolio->is_active ?? true))>
                        Tampilkan di situs
                    </label>
                </div>
                <div class="field">
                    <label>Unggulan</label>
                    <label class="checkbox">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $portfolio->is_featured))>
                        Tampilkan juga di deretan karya pilihan
                    </label>
                </div>
            </div>
        </div>

        @unless ($baru)
            <div class="card">
                <h2>Tambah Foto Lain</h2>
                <div class="field">
                    <label for="images">Pilih beberapa foto sekaligus</label>
                    <input type="file" name="images[]" id="images" accept=".jpg,.jpeg,.png" multiple>
                    <small class="hint">{{ \App\Support\UploadHelper::batchHint() }}</small>
                </div>
            </div>
        @endunless

        <button type="submit" class="btn btn-red">
            <i class="fa-solid fa-floppy-disk"></i> {{ $baru ? 'SIMPAN & LANJUT UNGGAH FOTO' : 'SIMPAN PERUBAHAN' }}
        </button>
    </form>

    @unless ($baru)
        <div class="card" style="margin-top: 24px;">
            <h2>Foto Karya Ini ({{ $portfolio->images->count() }})</h2>

            @if ($portfolio->images->isEmpty())
                <p class="muted">Belum ada foto tambahan. Unggah lewat kolom di atas.</p>
            @else
                <div class="photo-grid">
                    @foreach ($portfolio->images as $image)
                        <figure class="photo-item">
                            <img src="{{ asset($image->image) }}" alt="{{ $portfolio->title }}">
                            <form method="POST"
                                  action="{{ route('admin.portfolios.images.destroy', [$portfolio, $image]) }}"
                                  onsubmit="return confirm('Hapus foto ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="photo-remove" title="Hapus foto">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </figure>
                    @endforeach
                </div>
            @endif
        </div>
    @endunless
@endsection
