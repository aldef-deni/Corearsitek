<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /**
     * Salin sebuah baris dan letakkan tepat di bawah aslinya.
     *
     * Namanya diberi akhiran "(salinan)" supaya jelas mana yang baru, dan
     * baris lain yang urutannya bertabrakan digeser turun agar salinannya
     * benar-benar muncul berdampingan dengan sumbernya.
     *
     * @param  string  $kolomNama  Kolom yang memuat nama, mis. "label" atau "title".
     */
    protected function duplikatBaris(Model $baris, string $kolomNama): Model
    {
        $salinan = $baris->replicate();
        $salinan->{$kolomNama} = Str::limit((string) $baris->{$kolomNama}, 240, '') . ' (salinan)';
        $salinan->sort_order = (int) $baris->sort_order + 1;
        $salinan->save();

        $baris->newQuery()
            ->whereKeyNot($salinan->getKey())
            ->where('sort_order', '>=', $salinan->sort_order)
            ->increment('sort_order');

        return $salinan;
    }
}
