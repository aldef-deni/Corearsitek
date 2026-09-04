<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Feature;
use App\Models\Portfolio;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\SiteContent;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $contents = SiteContent::pluck('value', 'key')->toArray();

        return view('home', [
            'contents' => $contents,
            'banners' => Banner::active()->forPage('home')->orderBy('sort_order')->orderBy('id')->get(),
            'services' => Service::orderBy('sort_order')->get(),
            'features' => Feature::orderBy('sort_order')->get(),
            'portfolios' => Portfolio::active()->ordered()->take(5)->get(),
            'steps' => ProcessStep::orderBy('sort_order')->orderBy('id')->get(),
            'testimonials' => Testimonial::active()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
