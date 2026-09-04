<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Testimoni ----------
        $testimonials = [
            ['Bapak Andi Prasetya', 'Pemilik Rumah, Jakarta Selatan', 'Prosesnya rapi dari awal. Revisi ditanggapi cepat dan hasil rendernya sangat mendekati bangunan jadinya.', 1],
            ['Ibu Marlina Wijaya', 'Pemilik Villa, Bali', 'Desainnya paham betul iklim tropis. Sirkulasi udara dan cahaya alaminya terasa sekali setelah dibangun.', 2],
            ['Bapak Reza Kurniawan', 'Pengembang, Surabaya', 'Gambar teknisnya lengkap dan detail, kontraktor di lapangan tidak banyak bertanya lagi. Sangat membantu.', 3],
        ];

        foreach ($testimonials as [$name, $role, $quote, $sort]) {
            Testimonial::updateOrCreate(['name' => $name], [
                'role' => $role,
                'quote' => $quote,
                'is_active' => true,
                'sort_order' => $sort,
            ]);
        }
    }
}
