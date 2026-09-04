<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    /** Tahap penanganan sebuah pengajuan. */
    public const STATUSES = [
        'baru' => 'Baru',
        'dihubungi' => 'Sudah Dihubungi',
        'penawaran' => 'Penawaran Dikirim',
        'deal' => 'Deal',
        'batal' => 'Batal',
    ];

    /** Pilihan anggaran pada formulir; sengaja berupa rentang, bukan angka pasti. */
    public const BUDGETS = [
        '< 500 juta',
        '500 juta – 1 miliar',
        '1 – 2 miliar',
        '2 – 5 miliar',
        '> 5 miliar',
        'Belum ditentukan',
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'service_type',
        'location',
        'land_area',
        'building_area',
        'floors',
        'budget',
        'style',
        'message',
        'status',
        'is_read',
        'admin_note',
        'email_sent_at',
        'email_error',
        'ip_address',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    /**
     * Pencarian sederhana pada kolom yang paling sering dicari admin.
     */
    public function scopeSearch($query, ?string $term)
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            foreach (['name', 'phone', 'email', 'location', 'message'] as $kolom) {
                $q->orWhere($kolom, 'like', '%' . $term . '%');
            }
        });
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /** Nomor WhatsApp calon klien dalam bentuk yang bisa langsung diklik. */
    public function whatsappUrl(): string
    {
        $angka = preg_replace('/\D/', '', (string) $this->phone);

        // Nomor lokal yang diawali 0 diubah ke format internasional.
        if (str_starts_with($angka, '0')) {
            $angka = '62' . substr($angka, 1);
        }

        return 'https://wa.me/' . $angka;
    }

    /**
     * Baris ringkasan proyek untuk ditampilkan berderet, hanya yang terisi.
     *
     * @return array<string, string>
     */
    public function details(): array
    {
        return array_filter([
            'Jenis Layanan' => $this->service_type,
            'Lokasi Proyek' => $this->location,
            'Luas Tanah' => $this->land_area ? $this->land_area . ' m²' : null,
            'Luas Bangunan' => $this->building_area ? $this->building_area . ' m²' : null,
            'Jumlah Lantai' => $this->floors,
            'Perkiraan Anggaran' => $this->budget,
            'Gaya Desain' => $this->style,
        ]);
    }
}
