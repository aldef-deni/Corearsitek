<?php

namespace App\Support;

use Illuminate\Support\Facades\App;

/**
 * Pengelolaan dua bahasa situs: Indonesia sebagai bawaan, Inggris sebagai
 * pilihan. Semua tempat yang perlu tahu bahasa aktif memanggil kelas ini
 * supaya aturannya cuma ada di satu berkas.
 */
class Bahasa
{
    /** Bahasa yang didukung: kode => [label pendek, nama lengkap, kode HTML lang]. */
    public const PILIHAN = [
        'id' => ['ID', 'Bahasa Indonesia', 'id-ID'],
        'en' => ['EN', 'English', 'en-US'],
    ];

    public const BAWAAN = 'id';

    /** Nama cookie tempat pilihan bahasa disimpan. */
    public const COOKIE = 'bahasa';

    /** Berapa lama pilihan bahasa diingat (satu tahun, dalam menit). */
    public const UMUR_COOKIE = 525600;

    public static function aktif(): string
    {
        return self::sah(App::getLocale());
    }

    /** Pastikan sebuah kode bahasa memang didukung. */
    public static function sah(?string $kode): string
    {
        return isset(self::PILIHAN[$kode]) ? $kode : self::BAWAAN;
    }

    public static function inggris(): bool
    {
        return self::aktif() === 'en';
    }

    /** Kode untuk atribut lang pada tag <html>. */
    public static function kodeHtml(): string
    {
        return self::PILIHAN[self::aktif()][2];
    }

    /** Label pendek bahasa yang sedang aktif, mis. "ID". */
    public static function label(): string
    {
        return self::PILIHAN[self::aktif()][0];
    }

    /**
     * Bahasa yang akan dituju kalau tombol pengalih ditekan — dengan dua
     * pilihan, tombolnya cukup berpindah ke satu-satunya bahasa lain.
     */
    public static function lawan(): string
    {
        return self::inggris() ? 'id' : 'en';
    }
}
