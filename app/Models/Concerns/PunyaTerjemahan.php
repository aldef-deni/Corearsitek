<?php

namespace App\Models\Concerns;

use App\Support\Bahasa;

/**
 * Mengambil teks sesuai bahasa yang sedang aktif.
 *
 * Aturannya sengaja dibuat satu arah: kolom "_en" hanya dipakai kalau bahasa
 * Inggris sedang aktif DAN kolomnya terisi. Bidang yang belum diterjemahkan
 * kembali ke bahasa Indonesia, jadi halaman tidak pernah menampilkan bagian
 * kosong hanya karena terjemahannya belum sempat diisi.
 */
trait PunyaTerjemahan
{
    public function t(string $kolom): ?string
    {
        if (Bahasa::inggris()) {
            $inggris = $this->{$kolom . '_en'} ?? null;

            if (filled($inggris)) {
                return $inggris;
            }
        }

        return $this->{$kolom};
    }
}
