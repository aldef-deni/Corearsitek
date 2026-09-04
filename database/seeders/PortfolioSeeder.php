<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $karya = [
            [
                'title' => 'Desain Rumah Klasik Modern 2 Lantai Bapak AS di Jakarta Selatan',
                'category' => 'desain-rumah', 'style' => 'Klasik',
                'client' => 'Bapak AS', 'location' => 'Jakarta Selatan',
                'floors' => 2, 'building_area' => 485, 'land_width' => 20, 'land_length' => 20,
                'project_date' => '2026-08-24', 'image' => 'uploads/seed/gallery-1.svg',
                'featured' => true, 'sort' => 1,
                'description' => 'Hunian dua lantai bergaya neoklasik dengan taman tropis di sisi depan. Kolom dan pedimen memberi kesan megah tanpa berlebihan.',
            ],
            [
                'title' => 'Desain Villa Tropis 2 Lantai Ibu MW di Badung, Bali',
                'category' => 'desain-villa', 'style' => 'Villa Bali',
                'client' => 'Ibu MW', 'location' => 'Badung, Bali',
                'floors' => 2, 'building_area' => 535, 'land_width' => 30, 'land_length' => 27.65,
                'project_date' => '2026-08-12', 'image' => 'uploads/seed/gallery-2.svg',
                'featured' => true, 'sort' => 2,
                'description' => 'Villa dengan kolam renang dan lansekap hijau. Bukaan lebar mengalirkan udara sekaligus menjaga privasi penghuni.',
            ],
            [
                'title' => 'Desain Rumah Minimalis Mewah 1 Lantai Bapak RK di Surabaya',
                'category' => 'desain-rumah', 'style' => 'Modern',
                'client' => 'Bapak RK', 'location' => 'Surabaya, Jawa Timur',
                'floors' => 1, 'building_area' => 216, 'land_width' => 11.8, 'land_length' => 16.1,
                'project_date' => '2026-07-29', 'image' => 'uploads/seed/gallery-3.svg',
                'featured' => false, 'sort' => 3,
                'description' => 'Desain minimalis elegan dengan material premium dan garis atap yang tegas.',
            ],
            [
                'title' => 'Desain Townhouse Klasik 3 Lantai Bapak HR di Bandung',
                'category' => 'desain-bangunan-lain', 'style' => 'Klasik',
                'client' => 'Bapak HR', 'location' => 'Bandung, Jawa Barat',
                'floors' => 3, 'building_area' => 1089, 'land_width' => 28, 'land_length' => 23,
                'project_date' => '2026-07-03', 'image' => 'uploads/seed/gallery-4.svg',
                'featured' => false, 'sort' => 4,
                'description' => 'Deretan townhouse dengan fasad simetris nan anggun, dirancang untuk kawasan hunian padat.',
            ],
            [
                'title' => 'Desain Rumah Neo Klasik 2 Lantai Ibu CT di Bandar Lampung',
                'category' => 'desain-rumah', 'style' => 'Mediteran',
                'client' => 'Ibu CT', 'location' => 'Bandar Lampung',
                'floors' => 2, 'building_area' => 599, 'land_width' => 21, 'land_length' => 16,
                'project_date' => '2026-06-18', 'image' => 'uploads/seed/gallery-5.svg',
                'featured' => true, 'sort' => 5,
                'description' => 'Sentuhan kolom dan pedimen mediteran untuk kesan megah, dipadu bukaan lebar khas iklim tropis.',
            ],
            [
                'title' => 'Desain Interior Ruang Keluarga Bapak DW di Depok',
                'category' => 'desain-interior', 'style' => 'Kontemporer',
                'client' => 'Bapak DW', 'location' => 'Depok, Jawa Barat',
                'floors' => 1, 'building_area' => 68, 'land_width' => null, 'land_length' => null,
                'project_date' => '2026-05-30', 'image' => 'uploads/seed/gallery-6.svg',
                'featured' => false, 'sort' => 6,
                'description' => 'Penataan ruang keluarga dengan material hangat dan pencahayaan berlapis.',
            ],
        ];

        foreach ($karya as $k) {
            // Kunci pencarian harus slug yang stabil. Portfolio::uniqueSlug()
            // justru menghasilkan slug baru bila yang lama sudah ada, sehingga
            // seeder yang dijalankan dua kali akan menduplikasi datanya.
            Portfolio::updateOrCreate(
                ['slug' => Str::slug($k['title'])],
                [
                    'title' => $k['title'],
                    'category' => $k['category'],
                    'style' => $k['style'],
                    'client' => $k['client'],
                    'location' => $k['location'],
                    'floors' => $k['floors'],
                    'building_area' => $k['building_area'],
                    'land_width' => $k['land_width'],
                    'land_length' => $k['land_length'],
                    'project_date' => $k['project_date'],
                    'description' => $k['description'],
                    'cover_image' => $k['image'],
                    'is_featured' => $k['featured'],
                    'is_active' => true,
                    'sort_order' => $k['sort'],
                ]
            );
        }
    }
}
