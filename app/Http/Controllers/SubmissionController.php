<?php

namespace App\Http\Controllers;

use App\Mail\SubmissionReceived;
use App\Models\Submission;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        // Kolom umpan untuk robot: tidak terlihat manusia, jadi kalau terisi
        // permintaannya dibuang diam-diam tanpa memberi petunjuk apa pun.
        if ($request->filled('website')) {
            return back()->with('success', 'Terima kasih, pengajuan Anda sudah kami terima.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'service_type' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:150'],
            'land_area' => ['nullable', 'string', 'max:20'],
            'building_area' => ['nullable', 'string', 'max:20'],
            'floors' => ['nullable', 'string', 'max:20'],
            'budget' => ['nullable', 'string', 'max:60'],
            'style' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'name' => 'nama',
            'phone' => 'nomor WhatsApp',
            'message' => 'kebutuhan desain',
        ]);

        $data['ip_address'] = $request->ip();

        $submission = Submission::create($data);

        $this->kirimEmail($submission);

        return back()
            ->with('submission_success', true)
            ->with('success', 'Terima kasih, pengajuan Anda sudah kami terima. Tim CoreArsitek akan menghubungi Anda.')
            ->withFragment('form');
    }

    /**
     * Kirim pemberitahuan ke email penerima. Kegagalan pengiriman tidak boleh
     * menggagalkan pengajuan yang sudah tersimpan, jadi hasilnya hanya dicatat
     * pada barisnya sendiri supaya admin bisa melihat statusnya.
     */
    private function kirimEmail(Submission $submission): void
    {
        $tujuan = SiteContent::where('key', 'submission_email')->value('value')
            ?: config('mail.from.address');

        if (! $tujuan) {
            $submission->update(['email_error' => 'Alamat email penerima belum diisi.']);

            return;
        }

        try {
            Mail::to($tujuan)->send(new SubmissionReceived($submission));

            $submission->update(['email_sent_at' => now(), 'email_error' => null]);
        } catch (\Throwable $e) {
            Log::error('Pengiriman email pengajuan gagal', [
                'submission_id' => $submission->id,
                'pesan' => $e->getMessage(),
            ]);

            $submission->update(['email_error' => mb_strimwidth($e->getMessage(), 0, 250, '…')]);
        }
    }
}
