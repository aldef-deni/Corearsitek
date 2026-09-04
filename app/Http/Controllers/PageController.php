<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteContent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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

    public function portfolio(Request $request)
    {
        $category = $request->query('kategori');

        $portfolios = Portfolio::with('images')
            ->active()
            ->category($category)
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        // Jumlah per kategori dipakai untuk angka di samping tab penyaring.
        $counts = Portfolio::active()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('pages.portfolio', [
            'contents' => $this->contents(),
            'banners' => $this->banners('portfolio'),
            'portfolios' => $portfolios,
            'category' => $category,
            'counts' => $counts,
            'total' => $counts->sum(),
        ]);
    }

    public function portfolioDetail(Portfolio $portfolio)
    {
        abort_unless($portfolio->is_active, 404);

        $portfolio->load('images');

        return view('pages.portfolio-detail', [
            'contents' => $this->contents(),
            'portfolio' => $portfolio,
            'related' => Portfolio::active()
                ->where('category', $portfolio->category)
                ->whereKeyNot($portfolio->id)
                ->ordered()
                ->take(3)
                ->get(),
        ]);
    }

    public function gallery()
    {
        return view('pages.gallery', [
            'contents' => $this->contents(),
            'banners' => $this->banners('gallery'),
            'galleries' => Gallery::orderBy('sort_order')->orderBy('id')->get(),
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
            'clients' => Client::active()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
