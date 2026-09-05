<?php

namespace App\Models;

use App\Models\Concerns\PunyaTerjemahan;
use App\Support\Bahasa;
use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    use PunyaTerjemahan;

    protected $fillable = ['key', 'label', 'type', 'value', 'value_en'];

    /**
     * Seluruh konten situs sebagai pasangan kunci => teks, sudah mengikuti
     * bahasa yang sedang aktif. Kunci yang terjemahannya belum diisi tetap
     * memakai teks Indonesia, jadi halaman tidak pernah kosong.
     *
     * @return array<string, string|null>
     */
    public static function teks(): array
    {
        $inggris = Bahasa::inggris();

        return static::all()
            ->mapWithKeys(fn ($baris) => [
                $baris->key => $inggris && filled($baris->value_en) ? $baris->value_en : $baris->value,
            ])
            ->toArray();
    }
}
