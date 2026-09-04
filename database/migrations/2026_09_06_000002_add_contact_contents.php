<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Isi halaman Kontak dan alamat tujuan pemberitahuan pengajuan.
 * Semuanya disimpan sebagai konten situs biasa agar bisa diubah
 * lewat dashboard tanpa menyentuh berkas .env.
 */
return new class extends Migration
{
    private array $items = [
        ['key' => 'contact_title', 'label' => 'Judul Halaman Kontak', 'type' => 'text',
         'value' => 'HUBUNGI COREARSITEK'],

        ['key' => 'contact_intro', 'label' => 'Pengantar Halaman Kontak', 'type' => 'textarea',
         'value' => 'Ceritakan rencana hunian Anda. Isi formulir di bawah ini, tim kami akan menghubungi kembali untuk membahas kebutuhan, anggaran, dan tahapan pengerjaannya.'],

        ['key' => 'contact_hours', 'label' => 'Jam Operasional', 'type' => 'text',
         'value' => 'Senin – Sabtu, 09.00 – 17.00 WIB'],

        ['key' => 'contact_maps_url', 'label' => 'Tautan Google Maps', 'type' => 'text', 'value' => ''],

        ['key' => 'submission_email', 'label' => 'Email Penerima Pengajuan', 'type' => 'text',
         'value' => 'corearsitek@gmail.com'],
    ];

    public function up(): void
    {
        foreach ($this->items as $item) {
            if (! DB::table('site_contents')->where('key', $item['key'])->exists()) {
                DB::table('site_contents')->insert($item + [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('site_contents')
            ->whereIn('key', array_column($this->items, 'key'))
            ->delete();
    }
};
