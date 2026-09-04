<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;

abstract class Controller
{
    /**
     * Kembali ke halaman asal sambil menandai baris yang barusan diubah.
     *
     * Halaman-halaman daftar di dashboard bisa sangat panjang. Tanpa penanda,
     * setiap simpan melempar tampilan kembali ke paling atas dan pengguna
     * harus menggulir ulang mencari baris yang tadi dikerjakan. Fragmen ini
     * membuat peramban berhenti tepat di baris tersebut.
     *
     * @param  string  $awalan  Nama kelompok, mis. "fitur" atau "klien".
     * @param  Model|null  $baris  Baris yang jadi tujuan; null berarti menuju
     *                             judul kelompoknya (dipakai setelah menghapus).
     */
    protected function kembaliKeBaris(string $awalan, ?Model $baris, string $pesan)
    {
        $fragmen = 'baris-' . $awalan . ($baris ? '-' . $baris->getKey() : '');

        return back()->withFragment($fragmen)->with('success', $pesan);
    }
}
