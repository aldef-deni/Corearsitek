<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Akun admin ----------
        // Repositori ini publik, jadi password admin tidak boleh ditulis di sini.
        // Ambil dari ADMIN_PASSWORD di .env; kalau kosong, buat acak dan tampilkan
        // sekali saja saat akun pertama dibuat.
        $adminEmail = env('ADMIN_EMAIL', 'admin@corearsitek.com');
        $admin = User::where('email', $adminEmail)->first();

        if ($admin) {
            // Jangan pernah timpa password akun yang sudah dipakai.
            $admin->forceFill(['is_admin' => true])->save();
        } else {
            $password = env('ADMIN_PASSWORD') ?: Str::password(20);

            User::create([
                'name' => 'Administrator',
                'email' => $adminEmail,
                'password' => Hash::make($password),
                'is_admin' => true,
            ]);

            $this->command?->warn("Akun admin dibuat: {$adminEmail}");

            if (! env('ADMIN_PASSWORD')) {
                $this->command?->warn("Password acak: {$password}");
                $this->command?->warn('Simpan sekarang — password ini tidak ditampilkan lagi.');
            }
        }

        // ---------- Konten situs ----------
        $contents = [
            // Identitas
            ['site_name', 'Nama Situs', 'text', 'COREARSITEK'],
            ['logo_image', 'Logo Situs', 'image', 'images/logo.png'],
            ['og_image', 'Gambar Share Sosial (OG)', 'image', 'images/og-image.jpg'],
            // Hero
            ['hero_title', 'Judul Hero', 'text', 'JASA DESAIN RUMAH MEWAH'],
            ['hero_subtitle', 'Subjudul Hero', 'text', 'Hunian Aman, Nyaman dan Elegan'],
            ['hero_image', 'Gambar Hero', 'image', 'uploads/seed/hero.svg'],
            ['award_badge', 'Badge Penghargaan', 'text', 'LUXURY LIFESTYLE AWARDS WINNER 2023'],
            // Angka / stats
            ['stats_number', 'Angka Statistik', 'text', '2707'],
            ['stats_label', 'Label Statistik', 'text', 'Desain Finish s.d. 04-09-2026'],
            // Tentang
            ['about_title', 'Judul Tentang Kami', 'text', 'TENTANG COREARSITEK'],
            ['about_text', 'Teks Tentang Kami', 'textarea', "CoreArsitek adalah biro jasa desain arsitektur yang berfokus pada hunian mewah dan bergaya klasik-modern. Kami menghadirkan rumah yang aman, nyaman, dan elegan melalui pendekatan desain yang detail — mulai dari denah 2D, model 3D, hingga visual render yang realistis."],
            // Kontak
            ['contact_address', 'Alamat', 'text', 'Jl. Mewah Raya No. 88, Jakarta Selatan'],
            ['contact_phone', 'Telepon', 'text', '+62 812-3456-7890'],
            ['contact_email', 'Email', 'text', 'info@corearsitek.com'],
            ['whatsapp_number', 'Nomor WhatsApp', 'text', '6281234567890'],
            // Footer
            ['footer_text', 'Teks Footer', 'textarea', 'CoreArsitek — jasa desain arsitektur untuk hunian mewah. Hunian aman, nyaman, dan elegan.'],
            ['footer_copyright', 'Copyright Footer', 'text', '© 2026 CoreArsitek. All rights reserved.'],
            // SEO
            ['meta_description', 'Meta Description (SEO)', 'textarea', 'CoreArsitek — biro jasa desain arsitektur untuk hunian mewah. Jasa desain rumah, villa, interior, renovasi, dan bangunan lain. Hunian aman, nyaman, dan elegan.'],
            ['meta_keywords', 'Meta Keywords (SEO)', 'text', 'jasa desain rumah, desain villa, desain interior, arsitek rumah mewah, CoreArsitek'],
        ];

        foreach ($contents as [$key, $label, $type, $value]) {
            SiteContent::updateOrCreate(['key' => $key], [
                'label' => $label,
                'type' => $type,
                'value' => $value,
            ]);
        }

        // ---------- Layanan ----------
        $services = [
            ['JASA DESAIN RUMAH', 'Denah, tampak, dan render 3D untuk hunian mewah.', 'fa-house-chimney', 1],
            ['JASA DESAIN VILLA', 'Desain villa tropis maupun modern yang memukau.', 'fa-hotel', 2],
            ['JASA DESAIN INTERIOR', 'Tata interior nyaman, elegan, dan fungsional.', 'fa-couch', 3],
            ['JASA DESAIN RUMAH + INTERIOR', 'Paket lengkap arsitektur dan interior dalam satu tangan.', 'fa-layer-group', 4],
            ['JASA DESAIN BANGUNAN LAIN', 'Ruko, kantor, dan bangunan komersial lainnya.', 'fa-city', 5],
            ['JASA DESAIN RENOVASI RUMAH', 'Peremajaan hunian lama menjadi lebih modern.', 'fa-hammer', 6],
        ];

        foreach ($services as $i => [$title, $subtitle, $icon, $sort]) {
            Service::updateOrCreate(['title' => $title], [
                'subtitle' => $subtitle,
                'icon' => $icon,
                'sort_order' => $sort,
            ]);
        }

        // ---------- Keunggulan (Apa yang Anda dapatkan?) ----------
        $features = [
            ['fa-crown', 'Simbol & Status Elit', 1],
            ['fa-landmark', 'Tampilan Megah & Elegan CoreArsitek', 2],
            ['fa-border-all', 'Tata Ruang Lega', 3],
            ['fa-couch', 'Interior Lebih Nyaman', 4],
            ['fa-shield-halved', 'Struktur Lebih Aman', 5],
            ['fa-infinity', 'Long Lasting Style', 6],
            ['fa-window-restore', 'Cahaya Terang Alami', 7],
            ['fa-person-walking-arrow-right', 'Sirkulasi Hybrid', 8],
            ['fa-cubes', 'Material Berkelas', 9],
            ['fa-building', 'Fasilitas Lengkap', 10],
            ['fa-up-right-and-down-left-from-center', 'Optimalisasi Lahan Kecil & Besar', 11],
            ['fa-list-check', 'Quality Control Berlapis', 12],
            ['fa-comments', 'Konsultasi Gratis', 13],
            ['fa-file-pen', 'Revisi Sampai Puas', 14],
            ['fa-money-bill-wave', 'Pembayaran Bertahap', 15],
            ['fa-compass-drafting', 'Gambar Denah 2D', 16],
            ['fa-cube', 'Gambar Model 3D', 17],
            ['fa-house-circle-check', 'Visual Render Eksterior', 18],
            ['fa-display', 'Bonus Suggest 3D Visual Interior', 19],
            ['fa-folder-open', 'Gambar Teknis Arsitektur', 20],
            ['fa-trowel-bricks', 'Gambar Teknis Struktur', 21],
            ['fa-bolt', 'Gambar Teknis Elektrikal Plumbing', 22],
            ['fa-box-archive', 'Print Out A3 & Softcopy', 23],
            ['fa-file-shield', 'Mendapatkan RAB', 24],
        ];

        foreach ($features as [$icon, $label, $sort]) {
            Feature::updateOrCreate(['label' => $label], [
                'icon' => $icon,
                'sort_order' => $sort,
            ]);
        }

        // ---------- Galeri ----------
        $galleries = [
            ['Rumah Klasik Modern Jakarta', 'uploads/seed/gallery-1.svg', 'Hunian dua lantai bergaya neoklasik dengan taman tropis.', 1],
            ['Villa Tropis Bali', 'uploads/seed/gallery-2.svg', 'Villa mewah dengan kolam renang dan lansekap hijau.', 2],
            ['Rumah Minimalis Mewah', 'uploads/seed/gallery-3.svg', 'Desain minimalis elegan dengan material premium.', 3],
            ['Townhouse Klasik', 'uploads/seed/gallery-4.svg', 'Deretan townhouse dengan fasad simetris nan anggun.', 4],
            ['Rumah Neo Klasik', 'uploads/seed/gallery-5.svg', 'Sentuhan kolom dan pedimen untuk kesan megah.', 5],
            ['Villa Modern Tropis', 'uploads/seed/gallery-6.svg', 'Keseimbangan antara ruang terbuka dan privasi.', 6],
        ];

        foreach ($galleries as [$title, $image, $description, $sort]) {
            Gallery::updateOrCreate(['title' => $title], [
                'image' => $image,
                'description' => $description,
                'sort_order' => $sort,
            ]);
        }

        $this->call([
            BannerSeeder::class,
            PortfolioSeeder::class,
            ProcessStepSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
