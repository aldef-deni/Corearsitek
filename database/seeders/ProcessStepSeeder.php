<?php

namespace Database\Seeders;

use App\Models\ProcessStep;
use Illuminate\Database\Seeder;

class ProcessStepSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Proses kerja ----------
        $steps = [
            ['fa-comments', 'Hubungi Kami', 'Konsultasikan kebutuhan Anda via WhatsApp, telepon, atau email. Konsultasi awal gratis.', 1],
            ['fa-file-invoice', 'Proposal', 'Tim kami menyusun proposal lingkup kerja beserta biaya desain yang transparan.', 2],
            ['fa-compass-drafting', 'Desain', 'Setelah proposal disetujui, proses desain dimulai — denah 2D, model 3D, hingga render.', 3],
            ['fa-box-open', 'Penyerahan', 'Seluruh gambar kerja dan file desain dikirim lengkap, cetak A3 dan softcopy.', 4],
        ];

        foreach ($steps as [$icon, $title, $description, $sort]) {
            ProcessStep::updateOrCreate(['title' => $title], [
                'icon' => $icon,
                'description' => $description,
                'sort_order' => $sort,
            ]);
        }
    }
}
