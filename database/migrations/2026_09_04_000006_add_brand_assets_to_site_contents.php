<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Menambahkan konten aset merek (logo & gambar share) tanpa perlu menjalankan
 * ulang seeder, supaya konten yang sudah diubah lewat dashboard tetap aman.
 */
return new class extends Migration
{
    private array $items = [
        ['key' => 'logo_image', 'label' => 'Logo Situs', 'type' => 'image', 'value' => 'images/logo.png'],
        ['key' => 'og_image', 'label' => 'Gambar Share Sosial (OG)', 'type' => 'image', 'value' => 'images/og-image.jpg'],
    ];

    public function up(): void
    {
        foreach ($this->items as $item) {
            $exists = DB::table('site_contents')->where('key', $item['key'])->exists();

            if (! $exists) {
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
