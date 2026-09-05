<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Terjemahan Inggris untuk isi bawaan situs.
 *
 * Sengaja dicocokkan pada teks Indonesianya, bukan sekadar pada kuncinya:
 * kalau pemilik situs sudah mengubah teks aslinya, terjemahan bawaan ini
 * belum tentu cocok lagi, jadi lebih baik dilewati daripada memasang
 * terjemahan yang salah. Baris yang terjemahannya sudah diisi juga
 * tidak pernah ditimpa.
 */
return new class extends Migration
{
    /** key => [teks Indonesia yang diharapkan, terjemahan Inggris] */
    private array $konten = [
        'hero_title' => ['JASA DESAIN RUMAH MEWAH', 'LUXURY HOME DESIGN SERVICES'],
        'hero_subtitle' => ['Hunian Aman, Nyaman dan Elegan', 'Safe, Comfortable and Elegant Homes'],
        'stats_label' => ['Desain Finish s.d. 04-09-2026', 'Designs Completed as of 04-09-2026'],
        'about_title' => ['TENTANG COREARSITEK', 'ABOUT COREARSITEK'],
        'about_text' => [
            'CoreArsitek adalah biro jasa desain arsitektur yang berfokus pada hunian mewah bergaya klasik-modern.',
            'CoreArsitek is an architectural design studio focused on luxury homes in a classic-modern style.',
        ],
        'footer_text' => [
            'CoreArsitek — jasa desain arsitektur untuk hunian mewah. Hunian aman, nyaman, dan elegan.',
            'CoreArsitek — architectural design for luxury homes. Safe, comfortable, and elegant living.',
        ],
        'meta_description' => [
            'CoreArsitek — biro jasa desain arsitektur untuk hunian mewah. Jasa desain rumah, villa, interior, renovasi, dan bangunan lain. Hunian aman, nyaman, dan elegan.',
            'CoreArsitek — an architectural design studio for luxury homes. House, villa, interior, renovation, and commercial building design. Safe, comfortable, and elegant living.',
        ],
        'meta_keywords' => [
            'jasa desain rumah, desain villa, desain interior, arsitek rumah mewah, CoreArsitek',
            'house design service, villa design, interior design, luxury home architect, CoreArsitek',
        ],
        'about_tagline' => [
            'Hunian aman, nyaman, dan elegan — dirancang sampai ke detail terkecil.',
            'Safe, comfortable, and elegant homes — designed down to the smallest detail.',
        ],
        'about_vision' => [
            'Menjadi biro arsitektur yang dipercaya menghadirkan hunian mewah yang nyaman ditinggali.',
            'To be the architecture studio trusted to deliver luxury homes that are a genuine pleasure to live in.',
        ],
        'about_mission' => [
            "Merancang hunian yang memadukan estetika dan keamanan.\nMengutamakan pemahaman kebutuhan klien.\nMenyediakan gambar kerja yang siap dibangun.",
            "Design homes that balance beauty with structural safety.\nUnderstand what the client needs before drawing anything.\nDeliver working drawings that are ready to build from.",
        ],
        'about_profile_role' => ['Principal Architect', 'Principal Architect'],
        'about_profile_quote' => [
            'Rumah yang baik lahir dari mendengarkan, bukan dari menggambar duluan.',
            'A good home begins with listening, not with drawing.',
        ],
        'about_profile_bio' => [
            'Berpengalaman lebih dari satu dekade merancang hunian mewah dan villa di berbagai kota.',
            'More than a decade of experience designing luxury homes and villas across Indonesia.',
        ],
        'about_profile_skills' => [
            "Desain Arsitektur\nDesain Interior\nVisualisasi 3D\nGambar Kerja Teknis",
            "Architectural Design\nInterior Design\n3D Visualisation\nTechnical Working Drawings",
        ],
        'contact_title' => ['HUBUNGI COREARSITEK', 'CONTACT COREARSITEK'],
        'contact_intro' => [
            'Ceritakan rencana hunian Anda. Isi formulir di bawah ini, tim kami akan menghubungi kembali untuk membahas kebutuhan, anggaran, dan tahapan pengerjaannya.',
            'Tell us about the home you have in mind. Fill in the form below and our team will get back to you to discuss your needs, budget, and the stages of work.',
        ],
        'contact_hours' => ['Senin – Sabtu, 09.00 – 17.00 WIB', 'Monday – Saturday, 09.00 – 17.00 WIB'],
    ];

    /** Tabel => [kolom pencocok, kolom teks, [teks Indonesia => terjemahan]] */
    private function tabel(): array
    {
        return [
            ['services', 'title', [
                'JASA DESAIN RUMAH' => 'HOUSE DESIGN',
                'JASA DESAIN VILLA' => 'VILLA DESIGN',
                'JASA DESAIN INTERIOR' => 'INTERIOR DESIGN',
                'JASA DESAIN RUMAH + INTERIOR' => 'HOUSE + INTERIOR DESIGN',
                'JASA DESAIN BANGUNAN LAIN' => 'OTHER BUILDING DESIGN',
                'JASA DESAIN RENOVASI RUMAH' => 'HOME RENOVATION DESIGN',
            ]],
            ['services', 'subtitle', [
                'Denah, tampak, dan render 3D untuk hunian mewah.' => 'Floor plans, elevations, and 3D renders for luxury homes.',
                'Desain villa tropis maupun modern yang memukau.' => 'Striking tropical and modern villa designs.',
                'Tata interior nyaman, elegan, dan fungsional.' => 'Interiors that are comfortable, elegant, and practical.',
                'Paket lengkap arsitektur dan interior dalam satu tangan.' => 'Architecture and interior handled together, by one team.',
                'Ruko, kantor, dan bangunan komersial lainnya.' => 'Shophouses, offices, and other commercial buildings.',
                'Peremajaan hunian lama menjadi lebih modern.' => 'Bringing older homes up to date.',
            ]],
            ['process_steps', 'title', [
                'Hubungi Kami' => 'Get in Touch',
                'Proposal' => 'Proposal',
                'Desain' => 'Design',
                'Penyerahan' => 'Handover',
            ]],
            ['process_steps', 'description', [
                'Konsultasikan kebutuhan Anda via WhatsApp, telepon, atau email. Konsultasi awal gratis.'
                    => 'Talk through what you need by WhatsApp, phone, or email. The first consultation is free.',
                'Tim kami menyusun proposal lingkup kerja beserta biaya desain yang transparan.'
                    => 'Our team prepares a proposal covering the scope of work and a transparent design fee.',
                'Setelah proposal disetujui, proses desain dimulai — denah 2D, model 3D, hingga render.'
                    => 'Once the proposal is approved, design begins — 2D plans, 3D models, and renders.',
                'Seluruh gambar kerja dan file desain dikirim lengkap, cetak A3 dan softcopy.'
                    => 'All working drawings and design files are handed over, both A3 prints and digital copies.',
            ]],
            ['features', 'label', [
                'Simbol & Status Elit' => 'A Mark of Standing',
                'Tampilan Megah & Elegan CoreArsitek' => 'The Grand, Elegant CoreArsitek Look',
                'Tata Ruang Lega' => 'Generous, Well-Planned Space',
                'Interior Lebih Nyaman' => 'More Comfortable Interiors',
                'Struktur Lebih Aman' => 'A Safer Structure',
                'Long Lasting Style' => 'Long Lasting Style',
                'Cahaya Terang Alami' => 'Bright Natural Light',
                'Sirkulasi Hybrid' => 'Hybrid Air Circulation',
                'Material Berkelas' => 'Quality Materials',
                'Fasilitas Lengkap' => 'Complete Facilities',
                'Optimalisasi Lahan Kecil & Besar' => 'Optimised for Small and Large Plots',
                'Quality Control Berlapis' => 'Layered Quality Control',
                'Konsultasi Gratis' => 'Free Consultation',
                'Revisi Sampai Puas' => 'Revisions Until You Are Happy',
                'Pembayaran Bertahap' => 'Payment in Stages',
                'Gambar Denah 2D' => '2D Floor Plans',
                'Gambar Model 3D' => '3D Models',
                'Visual Render Eksterior' => 'Exterior Renders',
                'Bonus Suggest 3D Visual Interior' => 'Bonus 3D Interior Suggestions',
                'Gambar Teknis Arsitektur' => 'Architectural Working Drawings',
                'Gambar Teknis Struktur' => 'Structural Working Drawings',
                'Gambar Teknis Elektrikal Plumbing' => 'Electrical & Plumbing Drawings',
                'Print Out A3 & Softcopy' => 'A3 Prints & Digital Copies',
                'Mendapatkan RAB' => 'Bill of Quantities Included',
            ]],
            ['advantages', 'text', [
                'Tampilan bangunan tidak sesuai harapan' => 'The finished building looks nothing like you hoped',
                'Hunian terasa tidak nyaman ditinggali' => 'The house never feels comfortable to live in',
                'Miskomunikasi dan kecerobohan saat membangun' => 'Miscommunication and carelessness during construction',
                'Kesalahan struktur membuat bangunan rawan' => 'Structural mistakes leave the building unsafe',
                'Biaya membengkak di tengah pembangunan' => 'Costs balloon halfway through the build',
                'Kontraktor bekerja tanpa acuan gambar' => 'Contractors work with no drawings to follow',
                'Dibangun mahal tetapi sulit dijual kembali' => 'Expensive to build, hard to resell',
                'Tata ruang semrawut dan tidak efisien' => 'A cramped, inefficient layout',
                'Rawan ditipu karena tidak ada gambar kerja' => 'Easy to be overcharged with no working drawings',
                'Ruangan gelap, lembap, dan tidak sehat' => 'Dark, damp, unhealthy rooms',
                'Boros waktu dan tenaga akibat bongkar pasang' => 'Time and effort wasted on rework',
                'Hasil akhir terkesan asal jadi' => 'A finish that looks rushed',
                'Tampilan mewah dan elegan khas CoreArsitek' => 'The luxurious, elegant CoreArsitek look',
                'Perencanaan keamanan struktur yang matang' => 'Carefully engineered structural safety',
                'Tata ruang lega dengan sirkulasi dan cahaya alami' => 'Open layouts with natural light and airflow',
                'Quality control berlapis oleh beberapa arsitek' => 'Layered quality control by several architects',
                'Kenyamanan hunian bergaya villa' => 'The comfort of villa-style living',
                'Mendapat suggest interior 3D, video 3D, dan RAB' => '3D interior suggestions, 3D video, and a cost estimate',
                'Revisi sampai desainnya benar-benar memuaskan' => 'Revisions until the design is genuinely right',
                'Garansi desain yang dijamin bisa dibangun' => 'A design guaranteed to be buildable',
                'Optimal untuk lahan kecil maupun besar' => 'Optimised for small and large plots alike',
                'Bantuan koordinasi gambar selama pembangunan' => 'Drawing coordination support throughout construction',
            ]],
        ];
    }

    public function up(): void
    {
        foreach ($this->konten as $key => [$id, $en]) {
            DB::table('site_contents')
                ->where('key', $key)
                ->where('value', $id)
                ->where(fn ($q) => $q->whereNull('value_en')->orWhere('value_en', ''))
                ->update(['value_en' => $en]);
        }

        foreach ($this->tabel() as [$tabel, $kolom, $pasangan]) {
            foreach ($pasangan as $id => $en) {
                DB::table($tabel)
                    ->where($kolom, $id)
                    ->where(fn ($q) => $q->whereNull($kolom . '_en')->orWhere($kolom . '_en', ''))
                    ->update([$kolom . '_en' => $en]);
            }
        }
    }

    public function down(): void
    {
        // Terjemahan bawaan dibuang; teks Indonesianya tidak tersentuh.
        DB::table('site_contents')->whereIn('key', array_keys($this->konten))->update(['value_en' => null]);

        foreach ($this->tabel() as [$tabel, $kolom, $pasangan]) {
            DB::table($tabel)->whereIn($kolom . '_en', array_values($pasangan))->update([$kolom . '_en' => null]);
        }
    }
};
