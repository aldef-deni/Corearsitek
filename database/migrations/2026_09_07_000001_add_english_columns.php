<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom pendamping berbahasa Inggris untuk setiap teks yang tampil di situs.
 *
 * Bahasa Indonesia tetap jadi sumber utama; kolom "_en" hanya dipakai kalau
 * terisi. Dengan begitu situs tidak pernah kosong walau terjemahannya belum
 * lengkap — bagian yang belum diterjemahkan otomatis kembali ke bahasa
 * Indonesia, bukan menampilkan ruang kosong.
 */
return new class extends Migration
{
    /**
     * Tabel beserta kolom teks yang perlu pendamping bahasa Inggris.
     * Nilai "text" memakai tipe text, sisanya string biasa.
     */
    private array $peta = [
        'site_contents' => ['value' => 'text'],
        'services' => ['title' => 'string', 'subtitle' => 'string'],
        'features' => ['label' => 'string'],
        'advantages' => ['text' => 'string'],
        'process_steps' => ['title' => 'string', 'description' => 'text'],
        'testimonials' => ['role' => 'string', 'quote' => 'text'],
        'portfolios' => [
            'title' => 'string',
            'style' => 'string',
            'location' => 'string',
            'description' => 'text',
        ],
        'portfolio_images' => ['caption' => 'string'],
        'galleries' => ['title' => 'string', 'description' => 'text'],
        'banners' => [
            'title' => 'string',
            'subtitle' => 'string',
            'badge_text' => 'string',
            'button_text' => 'string',
        ],
    ];

    public function up(): void
    {
        foreach ($this->peta as $tabel => $kolom) {
            Schema::table($tabel, function (Blueprint $t) use ($tabel, $kolom) {
                foreach ($kolom as $nama => $tipe) {
                    $en = $nama . '_en';

                    if (Schema::hasColumn($tabel, $en)) {
                        continue;
                    }

                    if ($tipe === 'text') {
                        $t->text($en)->nullable()->after($nama);
                    } else {
                        $t->string($en)->nullable()->after($nama);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->peta as $tabel => $kolom) {
            Schema::table($tabel, function (Blueprint $t) use ($tabel, $kolom) {
                foreach (array_keys($kolom) as $nama) {
                    if (Schema::hasColumn($tabel, $nama . '_en')) {
                        $t->dropColumn($nama . '_en');
                    }
                }
            });
        }
    }
};
