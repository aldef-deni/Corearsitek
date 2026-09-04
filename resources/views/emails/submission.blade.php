<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengajuan Desain Baru</title>
</head>
{{-- Gaya ditulis inline: banyak klien email membuang blok <style>. --}}
<body style="margin:0;padding:24px;background:#f4f5f7;font-family:Arial,Helvetica,sans-serif;color:#1c1d21;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0"
       style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e3e5ea;border-radius:10px;overflow:hidden;">

    <tr>
        <td style="background:#0b0b0d;padding:22px 26px;">
            <div style="color:#ff1f1f;font-size:11px;letter-spacing:2.5px;font-weight:bold;">
                {{ $contents['site_name'] ?? 'COREARSITEK' }}
            </div>
            <div style="color:#ffffff;font-size:20px;font-weight:bold;margin-top:6px;">
                Pengajuan Desain Baru
            </div>
            <div style="color:#9a9da5;font-size:12px;margin-top:6px;">
                Masuk {{ $submission->created_at->translatedFormat('l, d F Y • H:i') }} WIB
            </div>
        </td>
    </tr>

    <tr>
        <td style="padding:26px;">

            <p style="margin:0 0 18px;font-size:14px;line-height:1.7;color:#4a4d55;">
                Ada calon klien yang mengirim pengajuan lewat halaman Kontak. Berikut datanya.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                <tr>
                    <td colspan="2" style="padding:0 0 8px;font-size:11px;letter-spacing:1.6px;
                        color:#ff1f1f;font-weight:bold;border-bottom:1px solid #e3e5ea;">
                        DATA PEMOHON
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 12px 6px 0;color:#83868f;width:170px;vertical-align:top;">Nama</td>
                    <td style="padding:12px 0 6px;font-weight:bold;">{{ $submission->name }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 12px 6px 0;color:#83868f;vertical-align:top;">Nomor WhatsApp</td>
                    <td style="padding:6px 0;">
                        <a href="{{ $submission->whatsappUrl() }}" style="color:#c81212;text-decoration:none;">
                            {{ $submission->phone }}
                        </a>
                    </td>
                </tr>
                @if ($submission->email)
                    <tr>
                        <td style="padding:6px 12px 6px 0;color:#83868f;vertical-align:top;">Email</td>
                        <td style="padding:6px 0;">
                            <a href="mailto:{{ $submission->email }}" style="color:#c81212;text-decoration:none;">
                                {{ $submission->email }}
                            </a>
                        </td>
                    </tr>
                @endif
            </table>

            @if ($submission->details())
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                       style="font-size:14px;margin-top:24px;">
                    <tr>
                        <td colspan="2" style="padding:0 0 8px;font-size:11px;letter-spacing:1.6px;
                            color:#ff1f1f;font-weight:bold;border-bottom:1px solid #e3e5ea;">
                            RINCIAN PROYEK
                        </td>
                    </tr>
                    @foreach ($submission->details() as $label => $nilai)
                        <tr>
                            <td style="padding:10px 12px 4px 0;color:#83868f;width:170px;vertical-align:top;">{{ $label }}</td>
                            <td style="padding:10px 0 4px;">{{ $nilai }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

            <div style="margin-top:24px;">
                <div style="padding:0 0 8px;font-size:11px;letter-spacing:1.6px;color:#ff1f1f;
                    font-weight:bold;border-bottom:1px solid #e3e5ea;">
                    KEBUTUHAN
                </div>
                <div style="margin-top:12px;padding:16px;background:#f7f8fa;border-radius:8px;
                    font-size:14px;line-height:1.75;white-space:pre-line;">{{ $submission->message }}</div>
            </div>

            <div style="margin-top:26px;">
                <a href="{{ route('admin.submissions.show', $submission) }}"
                   style="display:inline-block;padding:13px 26px;background:#e01414;color:#ffffff;
                   font-size:13px;font-weight:bold;letter-spacing:1px;text-decoration:none;border-radius:8px;">
                    BUKA DI DASHBOARD
                </a>
            </div>

        </td>
    </tr>

    <tr>
        <td style="padding:16px 26px;background:#f7f8fa;border-top:1px solid #e3e5ea;
            font-size:12px;color:#83868f;">
            Email otomatis dari situs {{ $contents['site_name'] ?? 'CoreArsitek' }}. Balas email ini untuk
            langsung menjawab calon klien.
        </td>
    </tr>

</table>

</body>
</html>
