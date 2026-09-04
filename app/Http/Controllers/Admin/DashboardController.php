<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\SiteContent;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'contentsCount' => SiteContent::count(),
            'servicesCount' => Service::count(),
            'featuresCount' => Feature::count(),
            'galleriesCount' => Gallery::count(),
        ]);
    }
}