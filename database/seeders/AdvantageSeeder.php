<?php

namespace Database\Seeders;

use App\Models\Advantage;
use Illuminate\Database\Seeder;

class AdvantageSeeder extends Seeder
{
    public function run(): void
    {
        $rugi = [
            'Tampilan bangunan tidak sesuai harapan',
            'Hunian terasa tidak nyaman ditinggali',
            'Miskomunikasi dan kecerobohan saat membangun',
            'Kesalahan struktur membuat bangunan rawan',
            'Biaya membengkak di tengah pembangunan',
            'Kontraktor bekerja tanpa acuan gambar',
            'Dibangun mahal tetapi sulit dijual kembali',
            'Tata ruang semrawut dan tidak efisien',
            'Rawan ditipu karena tidak ada gambar kerja',
            'Ruangan gelap, lembap, dan tidak sehat',
            'Boros waktu dan tenaga akibat bongkar pasang',
            'Hasil akhir terkesan asal jadi',
        ];

        $untung = [
            'Tampilan mewah dan elegan khas CoreArsitek',
            'Perencanaan keamanan struktur yang matang',
            'Tata ruang lega dengan sirkulasi dan cahaya alami',
            'Quality control berlapis oleh beberapa arsitek',
            'Kenyamanan hunian bergaya villa',
            'Mendapat suggest interior 3D, video 3D, dan RAB',
            'Revisi sampai desainnya benar-benar memuaskan',
            'Garansi desain yang dijamin bisa dibangun',
            'Optimal untuk lahan kecil maupun besar',
            'Bantuan koordinasi gambar selama pembangunan',
        ];

        foreach ([['rugi', $rugi], ['untung', $untung]] as [$type, $daftar]) {
            foreach ($daftar as $i => $teks) {
                Advantage::updateOrCreate(
                    ['type' => $type, 'text' => $teks],
                    ['sort_order' => $i + 1]
                );
            }
        }
    }
}
