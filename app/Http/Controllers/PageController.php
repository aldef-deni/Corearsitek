<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\SiteContent;
use Illuminate\Database\Eloquent\Collection;

class PageController extends Controller
{
    private function contents(): array
    {
        return SiteContent::pluck('value', 'key')->toArray();
    }

    private function banners(string $page): Collection
    {
        return Banner::active()->forPage($page)->orderBy('sort_order')->orderBy('id')->get();
    }

    public function portfolio()
    {
        return view('pages.portfolio', [
            'contents' => $this->contents(),
            'banners' => $this->banners('portfolio'),
            'galleries' => Gallery::orderBy('sort_order')->get(),
        ]);
    }

    public function services()
    {
        return view('pages.services', [
            'contents' => $this->contents(),
            'banners' => $this->banners('services'),
            'services' => Service::orderBy('sort_order')->get(),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'contents' => $this->contents(),
            'banners' => $this->banners('about'),
        ]);
    }
}
