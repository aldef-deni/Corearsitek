<?php

namespace App\Http\Controllers;

use App\Support\Bahasa;
use Illuminate\Support\Facades\Cookie;

class BahasaController extends Controller
{
    /**
     * Simpan pilihan bahasa lalu kembalikan pengunjung ke halaman semula,
     * supaya berpindah bahasa tidak membuang posisi bacaannya.
     */
    public function ganti(string $lokal)
    {
        return back()->withCookie(
            Cookie::make(Bahasa::COOKIE, Bahasa::sah($lokal), Bahasa::UMUR_COOKIE)
        );
    }
}
