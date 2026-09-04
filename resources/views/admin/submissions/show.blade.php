@extends('admin.layouts.app')

@section('title', 'Pengajuan — ' . $submission->name)

@section('content')
    <h1 class="page-title">
        {{ $submission->name }}
        <small>
            Masuk {{ $submission->created_at->translatedFormat('l, d F Y') }}
            pukul {{ $submission->created_at->format('H:i') }} WIB
        </small>
    </h1>

    <div class="toolbar">
        <a href="{{ route('admin.submissions.index') }}" class="btn btn-ghost">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <a href="{{ $submission->whatsappUrl() }}" target="_blank" rel="noopener" class="btn btn-red">
            <i class="fa-brands fa-whatsapp"></i> Hubungi via WhatsApp
        </a>
        @if ($submission->email)
            <a href="mailto:{{ $submission->email }}?subject={{ rawurlencode('Pengajuan Desain — CoreArsitek') }}"
               class="btn btn-outline-red">
                <i class="fa-regular fa-envelope"></i> Balas Email
            </a>
        @endif
    </div>

    @if ($submission->email_error)
        <div class="alert alert-error">
            Pemberitahuan email untuk pengajuan ini gagal terkirim: {{ $submission->email_error }}
            Datanya tetap tersimpan di sini.
        </div>
    @endif

    <div class="card">
        <h2>Data Pemohon</h2>
        <dl class="detail-list">
            <div>
                <dt>Nama</dt>
                <dd>{{ $submission->name }}</dd>
            </div>
            <div>
                <dt>Nomor WhatsApp</dt>
                <dd><a href="{{ $submission->whatsappUrl() }}" target="_blank" rel="noopener">{{ $submission->phone }}</a></dd>
            </div>
            <div>
                <dt>Email</dt>
                <dd>
                    @if ($submission->email)
                        <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
                    @else
                        <span class="muted">Tidak diisi</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt>Pemberitahuan Email</dt>
                <dd>
                    @if ($submission->email_sent_at)
                        Terkirim {{ $submission->email_sent_at->translatedFormat('d M Y H:i') }}
                    @elseif ($submission->email_error)
                        <span class="tag tag-off">Gagal</span>
                    @else
                        <span class="muted">Belum terkirim</span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>

    @if ($submission->details())
        <div class="card">
            <h2>Rincian Proyek</h2>
            <dl class="detail-list">
                @foreach ($submission->details() as $label => $nilai)
                    <div>
                        <dt>{{ $label }}</dt>
                        <dd>{{ $nilai }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    @endif

    <div class="card">
        <h2>Kebutuhan Desain</h2>
        <p class="detail-text">{{ $submission->message }}</p>
    </div>

    <div class="card">
        <h2>Tindak Lanjut</h2>
        <form method="POST" action="{{ route('admin.submissions.update', $submission) }}">
            @csrf
            @method('PUT')

            <div class="field field-sm">
                <label for="status">Status</label>
                <select name="status" id="status">
                    @foreach (\App\Models\Submission::STATUSES as $key => $label)
                        <option value="{{ $key }}" @selected($submission->status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="admin_note">Catatan Internal</label>
                <textarea name="admin_note" id="admin_note" rows="5"
                          placeholder="Hasil pembicaraan, kesepakatan harga, atau pengingat.">{{ old('admin_note', $submission->admin_note) }}</textarea>
                <small class="hint">Hanya terlihat oleh administrator, tidak dikirim ke calon klien.</small>
            </div>

            <button type="submit" class="btn btn-red"><i class="fa-solid fa-floppy-disk"></i> SIMPAN</button>
        </form>
    </div>

    <div class="card card-row">
        <div class="form-grow">
            <h3 class="row-title">Kelola Pengajuan</h3>
            <p class="row-meta">
                Dikirim dari alamat IP {{ $submission->ip_address ?: 'tidak tercatat' }}.
            </p>
        </div>
        <div class="card-row-actions">
            <form method="POST" action="{{ route('admin.submissions.read', $submission) }}">
                @csrf
                <button type="submit" class="btn btn-ghost">
                    <i class="fa-regular fa-envelope{{ $submission->is_read ? '' : '-open' }}"></i>
                    {{ $submission->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.submissions.destroy', $submission) }}"
                  onsubmit="return confirm('Hapus pengajuan dari {{ $submission->name }}? Tindakan ini tidak bisa dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
            </form>
        </div>
    </div>
@endsection
