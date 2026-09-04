<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\SiteContent;

class HomeController extends Controller
{
    public function index()
    {
        $contents = SiteContent::pluck('value', 'key')->toArray();
        $features = Feature::orderBy('sort_order')->get();

        return view('home', compact('contents', 'features'));
    }
}