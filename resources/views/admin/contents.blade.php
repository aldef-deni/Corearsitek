@extends('admin.layouts.app')

@section('title', 'Konten Situs')

@section('content')
    <h1 class="page-title">Konten Situs</h1>

    <form method="POST" action="{{ route('admin.contents.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <h2>Hero</h2>
            @foreach ($contents as $item)
                @if ($item->key === 'hero_image')
                    <div class="field">
                        <label>{{ $item->label }}</label>
                        <img src="{{ asset($item->value) }}" alt="{{ $item->label }}" class="thumb thumb-lg">
                        <input type="file" name="contents[{{ $item->id }}][image]" accept="image/*">
                        <small class="hint">Format JPG/PNG/SVG, maks 5MB.</small>
                    </div>
                @elseif (in_array($item->key, ['hero_title', 'hero_subtitle', 'award_badge'], true))
                    <div class="field">
                        <label>{{ $item->label }}</label>
                        <input type="text" name="contents[{{ $item->id }}][value]" value="{{ $item->value }}">
                    </div>
                @endif
            @endforeach
        </div>

        <div class="card">
            <h2>Statistik</h2>
            @foreach ($contents as $item)
                @if (in_array($item->key, ['stats_number', 'stats_label'], true))
                    <div class="field">
                        <label>{{ $item->label }}</label>
                        <input type="text" name="contents[{{ $item->id }}][value]" value="{{ $item->value }}">
                    </div>
                @endif
            @endforeach
        </div>

        <div class="card">
            <h2>Tentang Kami</h2>
            @foreach ($contents as $item)
                @if (in_array($item->key, ['about_title', 'about_text'], true))
                    <div class="field">
                        <label>{{ $item->label }}</label>
                        @if ($item->type === 'textarea')
                            <textarea name="contents[{{ $item->id }}][value]" rows="5">{{ $item->value }}</textarea>
                        @else
                            <input type="text" name="contents[{{ $item->id }}][value]" value="{{ $item->value }}">
                        @endif
                    </div>
                @endif
            @endforeach
        </div>

        <div class="card">
            <h2>Kontak</h2>
            @foreach ($contents as $item)
                @if (in_array($item->key, ['contact_address', 'contact_phone', 'contact_email', 'whatsapp_number'], true))
                    <div class="field">
                        <label>{{ $item->label }}@if ($item->key === 'whatsapp_number') <small>(format internasional, tanpa +)</small>@endif</label>
                        <input type="text" name="contents[{{ $item->id }}][value]" value="{{ $item->value }}">
                    </div>
                @endif
            @endforeach
        </div>

        <div class="card">
            <h2>SEO</h2>
            @foreach ($contents as $item)
                @if (in_array($item->key, ['meta_description', 'meta_keywords'], true))
                    <div class="field">
                        <label>{{ $item->label }}</label>
                        @if ($item->type === 'textarea')
                            <textarea name="contents[{{ $item->id }}][value]" rows="3">{{ $item->value }}</textarea>
                        @else
                            <input type="text" name="contents[{{ $item->id }}][value]" value="{{ $item->value }}">
                        @endif
                    </div>
                @endif
            @endforeach
        </div>

        <div class="card">
            <h2>Footer</h2>
            @foreach ($contents as $item)
                @if (in_array($item->key, ['site_name', 'footer_text', 'footer_copyright'], true))
                    <div class="field">
                        <label>{{ $item->label }}</label>
                        @if ($item->type === 'textarea')
                            <textarea name="contents[{{ $item->id }}][value]" rows="4">{{ $item->value }}</textarea>
                        @else
                            <input type="text" name="contents[{{ $item->id }}][value]" value="{{ $item->value }}">
                        @endif
                    </div>
                @endif
            @endforeach
        </div>

        <button type="submit" class="btn btn-red"><i class="fa-solid fa-floppy-disk"></i> Simpan Semua Konten</button>
    </form>
@endsection