<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Banner hero (bisa diubah admin) ----------
        $banners = [
            ['JASA DESAIN RUMAH MEWAH', 'Hunian Aman, Nyaman dan Elegan', 'LUXURY LIFESTYLE AWARDS WINNER 2023', 'uploads/seed/hero.svg', 'KONSULTASI GRATIS', '#kontak', 1],
            ['DESAIN VILLA EKSKLUSIF', 'Ketenangan tropis dalam garis yang tegas', 'PORTOFOLIO PILIHAN', 'uploads/seed/gallery-2.svg', 'LIHAT PORTOFOLIO', '/portofolio', 2],
            ['INTERIOR YANG BERKARAKTER', 'Detail rapi, material berkelas', 'INTERIOR STUDIO', 'uploads/seed/gallery-3.svg', 'LIHAT LAYANAN', '/layanan', 3],
        ];

        foreach ($banners as [$title, $subtitle, $badge, $image, $btnText, $btnUrl, $sort]) {
            Banner::updateOrCreate(['title' => $title], [
                'subtitle' => $subtitle,
                'badge_text' => $badge,
                'image' => $image,
                'button_text' => $btnText,
                'button_url' => $btnUrl,
                'is_active' => true,
                'sort_order' => $sort,
            ]);
        }
    }
}
