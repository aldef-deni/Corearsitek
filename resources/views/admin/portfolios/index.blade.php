@extends('admin.layouts.app')

@section('title', 'Portofolio')

@section('content')
    <h1 class="page-title">
        Portofolio
        <small>Karya yang tampil di halaman Portofolio. Klik sebuah karya untuk mengubahnya.</small>
    </h1>

    <div class="toolbar">
        <a href="{{ route('admin.portfolios.create') }}" class="btn btn-red">
            <i class="fa-solid fa-plus"></i> TAMBAH KARYA
        </a>
        <a href="{{ route('portfolio') }}" target="_blank" class="btn btn-outline-red">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Halaman
        </a>
    </div>

    <div class="filter-tabs">
        <a href="{{ route('admin.portfolios.index') }}" class="filter-tab {{ $category ? '' : 'is-active' }}">
            Semua <span>{{ $counts->sum() }}</span>
        </a>
        @foreach (\App\Models\Portfolio::CATEGORIES as $key => $label)
            <a href="{{ route('admin.portfolios.index', ['kategori' => $key]) }}"
               class="filter-tab {{ $category === $key ? 'is-active' : '' }}">
                {{ $label }} <span>{{ $counts[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    @forelse ($portfolios as $portfolio)
        <div class="card card-row">
            <div class="card-row-thumb card-row-thumb-wide">
                <img src="{{ asset($portfolio->cover_image) }}" alt="{{ $portfolio->title }}">
            </div>

            <div class="form-grow">
                <h3 class="row-title">
                    {{ $portfolio->title }}
                    @unless ($portfolio->is_active)
                        <span class="tag tag-off">Disembunyikan</span>
                    @endunless
                    @if ($portfolio->is_featured)
                        <span class="tag tag-on">Unggulan</span>
                    @endif
                </h3>

                <p class="row-meta">
                    <i class="fa-solid fa-layer-group"></i> {{ $portfolio->categoryLabel() }}
                    @if ($portfolio->style) &middot; {{ $portfolio->style }} @endif
                    @if ($portfolio->location) &middot; <i class="fa-solid fa-location-dot"></i> {{ $portfolio->location }} @endif
                    @if ($portfolio->project_date) &middot; {{ $portfolio->project_date->translatedFormat('d M Y') }} @endif
                    &middot; {{ $portfolio->images->count() }} foto tambahan
                </p>

                @if ($portfolio->specs())
                    <p class="row-meta">
                        @foreach ($portfolio->specs() as $spec)
                            <span class="spec">{{ $spec }}</span>
                        @endforeach
                    </p>
                @endif
            </div>

            <div class="row-actions">
                <a href="{{ route('admin.portfolios.edit', $portfolio) }}" class="btn btn-ghost">
                    <i class="fa-solid fa-pen"></i> Ubah
                </a>
                <form method="POST" action="{{ route('admin.portfolios.destroy', $portfolio) }}"
                      onsubmit="return confirm('Hapus karya &quot;{{ $portfolio->title }}&quot; beserta seluruh fotonya?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <p class="muted">
                Belum ada karya. Klik <strong>Tambah Karya</strong> untuk mulai mengisi halaman Portofolio.
            </p>
        </div>
    @endforelse

    {{ $portfolios->links() }}
@endsection
