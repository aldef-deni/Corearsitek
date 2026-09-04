<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advantage;
use App\Models\Banner;
use App\Models\Client;
use App\Models\Feature;
use App\Models\Gallery;
use App\Models\Portfolio;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\SiteContent;
use App\Models\Submission;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'submissionsCount' => Submission::count(),
            'submissionsUnread' => Submission::unread()->count(),
            'bannersCount' => Banner::count(),
            'contentsCount' => SiteContent::count(),
            'servicesCount' => Service::count(),
            'featuresCount' => Feature::count(),
            'galleriesCount' => Gallery::count(),
            'portfoliosCount' => Portfolio::count(),
            'stepsCount' => ProcessStep::count(),
            'testimonialsCount' => Testimonial::count(),
            'advantagesCount' => Advantage::count(),
            'clientsCount' => Client::count(),
        ]);
    }
}
