<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Service;
use App\Models\SiteContent;

class PageController extends Controller
{
    private function contents(): array
    {
        return SiteContent::pluck('value', 'key')->toArray();
    }

    public function portfolio()
    {
        $contents = $this->contents();
        $galleries = Gallery::orderBy('sort_order')->get();

        return view('pages.portfolio', compact('contents', 'galleries'));
    }

    public function services()
    {
        $contents = $this->contents();
        $services = Service::orderBy('sort_order')->get();

        return view('pages.services', compact('contents', 'services'));
    }

    public function about()
    {
        $contents = $this->contents();

        return view('pages.about', compact('contents'));
    }
}