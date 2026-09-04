<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array<string, string> Konten situs, dipakai untuk nama merek di email. */
    public array $contents;

    public function __construct(public Submission $submission)
    {
        $this->contents = \App\Models\SiteContent::pluck('value', 'key')->toArray();
    }

    public function envelope(): Envelope
    {
        $envelope = new Envelope(
            subject: 'Pengajuan Desain Baru — ' . $this->submission->name,
        );

        // Balasan diarahkan ke calon klien supaya admin tinggal tekan "Reply".
        if ($this->submission->email) {
            $envelope->replyTo = [
                new \Illuminate\Mail\Mailables\Address($this->submission->email, $this->submission->name),
            ];
        }

        return $envelope;
    }

    public function content(): Content
    {
        return new Content(view: 'emails.submission');
    }
}
