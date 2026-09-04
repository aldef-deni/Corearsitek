@extends('admin.layouts.app')

@section('title', 'Data Pengajuan')

@section('content')
    <h1 class="page-title">
        Data Pengajuan
        <small>Pengajuan pembuatan desain yang masuk dari halaman Kontak.</small>
    </h1>

    <div class="toolbar">
        <a href="{{ route('contact') }}" target="_blank" class="btn btn-outline-red">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Halaman Kontak
        </a>

        <form method="GET" action="{{ route('admin.submissions.index') }}" class="toolbar-search">
            @if ($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="search" name="cari" value="{{ $cari }}"
                   placeholder="Cari nama, nomor, email, atau lokasi…">
            <button type="submit" class="btn btn-ghost"><i class="fa-solid fa-magnifying-glass"></i></button>
            @if ($cari)
                <a href="{{ route('admin.submissions.index', ['status' => $status]) }}" class="btn btn-ghost">
                    Bersihkan
                </a>
            @endif
        </form>
    </div>

    <div class="filter-tabs">
        <a href="{{ route('admin.submissions.index', ['cari' => $cari]) }}"
           class="filter-tab {{ $status ? '' : 'is-active' }}">
            Semua <span>{{ $total }}</span>
        </a>
        @foreach (\App\Models\Submission::STATUSES as $key => $label)
            <a href="{{ route('admin.submissions.index', ['status' => $key, 'cari' => $cari]) }}"
               class="filter-tab {{ $status === $key ? 'is-active' : '' }}">
                {{ $label }} <span>{{ $counts[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    @if ($unread)
        <p class="muted" style="margin-bottom: 16px;">
            <i class="fa-solid fa-circle" style="color: var(--red); font-size: 8px;"></i>
            {{ $unread }} pengajuan belum dibaca.
        </p>
    @endif

    @forelse ($submissions as $submission)
        <a class="card card-row sub-row {{ $submission->is_read ? '' : 'is-unread' }}"
           href="{{ route('admin.submissions.show', $submission) }}">

            <div class="sub-when">
                <strong>{{ $submission->created_at->translatedFormat('d M Y') }}</strong>
                <span>{{ $submission->created_at->format('H:i') }}</span>
            </div>

            <div class="form-grow">
                <h3 class="row-title">
                    {{ $submission->name }}
                    <span class="tag tag-status tag-{{ $submission->status }}">{{ $submission->statusLabel() }}</span>
                    @unless ($submission->is_read)
                        <span class="tag tag-on">Baru</span>
                    @endunless
                    @if ($submission->email_error)
                        <span class="tag tag-off" title="{{ $submission->email_error }}">Email gagal</span>
                    @endif
                </h3>

                <p class="row-meta">
                    <i class="fa-brands fa-whatsapp"></i> {{ $submission->phone }}
                    @if ($submission->email) &middot; <i class="fa-regular fa-envelope"></i> {{ $submission->email }} @endif
                    @if ($submission->location) &middot; <i class="fa-solid fa-location-dot"></i> {{ $submission->location }} @endif
                </p>

                @if ($submission->details())
                    <p class="row-meta">
                        @foreach ($submission->details() as $label => $nilai)
                            <span class="spec">{{ $nilai }}</span>
                        @endforeach
                    </p>
                @endif

                <p class="sub-excerpt">{{ \Illuminate\Support\Str::limit($submission->message, 190) }}</p>
            </div>

            <span class="sub-open"><i class="fa-solid fa-chevron-right"></i></span>
        </a>
    @empty
        <div class="card">
            <p class="muted">
                @if ($cari || $status)
                    Tidak ada pengajuan yang cocok dengan penyaring ini.
                @else
                    Belum ada pengajuan masuk. Setiap formulir yang dikirim dari halaman Kontak
                    akan muncul di sini sekaligus dikirim ke email penerima.
                @endif
            </p>
        </div>
    @endforelse

    {{ $submissions->links() }}
@endsection
