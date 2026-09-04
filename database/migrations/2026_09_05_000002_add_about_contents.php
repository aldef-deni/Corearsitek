<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Isi halaman "Tentang CoreArsitek" disimpan sebagai konten situs biasa,
 * supaya bisa diubah lewat dashboard tanpa perlu tabel baru.
 */
return new class extends Migration
{
    private array $items = [
        ['key' => 'about_tagline', 'label' => 'Tagline Tentang', 'type' => 'text',
         'value' => 'Hunian aman, nyaman, dan elegan — dirancang sampai ke detail terkecil.'],

        ['key' => 'about_vision', 'label' => 'Visi', 'type' => 'textarea',
         'value' => 'Menjadi biro arsitektur yang dipercaya menghadirkan hunian mewah yang tidak hanya indah dipandang, tetapi nyaman ditinggali dan aman dalam jangka panjang.'],

        ['key' => 'about_mission', 'label' => 'Misi (satu poin per baris)', 'type' => 'textarea',
         'value' => "Merancang hunian yang memadukan estetika, kenyamanan, dan keamanan struktur.\nMengutamakan pemahaman kebutuhan klien sebelum menuangkannya ke dalam desain.\nMenyediakan gambar kerja yang lengkap dan siap dibangun di lapangan.\nMendampingi klien dari denah pertama hingga rumah berdiri."],

        ['key' => 'about_profile_image', 'label' => 'Foto Profil', 'type' => 'image', 'value' => ''],

        ['key' => 'about_profile_name', 'label' => 'Nama Profil', 'type' => 'text', 'value' => ''],

        ['key' => 'about_profile_role', 'label' => 'Jabatan Profil', 'type' => 'text',
         'value' => 'Principal Architect'],

        ['key' => 'about_profile_quote', 'label' => 'Kutipan Profil', 'type' => 'textarea', 'value' => ''],

        ['key' => 'about_profile_bio', 'label' => 'Deskripsi Profil', 'type' => 'textarea', 'value' => ''],

        ['key' => 'about_profile_skills', 'label' => 'Bidang Keahlian (satu per baris)', 'type' => 'textarea',
         'value' => "Desain Arsitektur\nDesain Interior\nPerencanaan Struktur\nVisualisasi 3D\nGambar Kerja Teknis\nManajemen Proyek"],
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
