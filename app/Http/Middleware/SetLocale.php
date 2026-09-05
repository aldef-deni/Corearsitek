<?php

namespace App\Http\Middleware;

use App\Support\Bahasa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Menetapkan bahasa tampilan dari pilihan pengunjung.
 *
 * Pilihannya disimpan di cookie, bukan session, supaya tetap diingat setelah
 * session kedaluwarsa dan tetap berlaku untuk pengunjung yang tidak pernah
 * mengisi formulir apa pun.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        App::setLocale(Bahasa::sah($request->cookie(Bahasa::COOKIE)));

        return $next($request);
    }
}
