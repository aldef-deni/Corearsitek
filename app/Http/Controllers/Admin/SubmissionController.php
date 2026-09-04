<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $cari = $request->query('cari');

        $submissions = Submission::status($status)
            ->search($cari)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Angka di samping tab penyaring.
        $counts = Submission::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.submissions.index', [
            'submissions' => $submissions,
            'status' => $status,
            'cari' => $cari,
            'counts' => $counts,
            'total' => $counts->sum(),
            'unread' => Submission::unread()->count(),
        ]);
    }

    public function show(Submission $submission)
    {
        // Membuka detailnya sekaligus menandainya sudah dibaca.
        if (! $submission->is_read) {
            $submission->update(['is_read' => true]);
        }

        return view('admin.submissions.show', ['submission' => $submission]);
    }

    public function update(Request $request, Submission $submission)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(Submission::STATUSES))],
            'admin_note' => ['nullable', 'string', 'max:3000'],
        ]);

        $submission->update($data);

        return back()->with('success', 'Pengajuan diperbarui.');
    }

    /**
     * Kembalikan ke status belum dibaca supaya gampang ditandai untuk
     * ditindaklanjuti nanti.
     */
    public function toggleRead(Submission $submission)
    {
        $submission->update(['is_read' => ! $submission->is_read]);

        return back()->with(
            'success',
            $submission->is_read ? 'Ditandai sudah dibaca.' : 'Ditandai belum dibaca.'
        );
    }

    public function destroy(Submission $submission)
    {
        $submission->delete();

        return redirect()
            ->route('admin.submissions.index')
            ->with('success', 'Pengajuan dihapus.');
    }
}
