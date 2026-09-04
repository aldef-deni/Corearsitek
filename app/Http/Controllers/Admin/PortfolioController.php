<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioImage;
use App\Support\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('kategori');

        $portfolios = Portfolio::with('images')
            ->category($category)
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.portfolios.index', [
            'portfolios' => $portfolios,
            'category' => $category,
            'counts' => Portfolio::selectRaw('category, count(*) as total')
                ->groupBy('category')
                ->pluck('total', 'category'),
        ]);
    }

    public function create()
    {
        return view('admin.portfolios.form', [
            'portfolio' => new Portfolio(['is_active' => true]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, coverRequired: true);

        $data['slug'] = Portfolio::uniqueSlug($data['title']);
        $data['cover_image'] = UploadHelper::image($request->file('cover_image'));
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $portfolio = Portfolio::create($data);

        $this->storeExtraImages($request, $portfolio);

        return redirect()
            ->route('admin.portfolios.edit', $portfolio)
            ->with('success', 'Karya berhasil ditambahkan. Silakan lengkapi foto tambahannya.');
    }

    public function edit(Portfolio $portfolio)
    {
        $portfolio->load('images');

        return view('admin.portfolios.form', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $data = $this->validated($request, coverRequired: false);

        if ($portfolio->title !== $data['title']) {
            $data['slug'] = Portfolio::uniqueSlug($data['title'], $portfolio->id);
        }

        if ($request->hasFile('cover_image')) {
            UploadHelper::deleteIfExists($portfolio->cover_image);
            $data['cover_image'] = UploadHelper::image($request->file('cover_image'));
        } else {
            unset($data['cover_image']);
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $portfolio->update($data);

        $this->storeExtraImages($request, $portfolio);

        return back()->with('success', 'Karya berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio)
    {
        foreach ($portfolio->images as $image) {
            UploadHelper::deleteIfExists($image->image);
        }

        UploadHelper::deleteIfExists($portfolio->cover_image);
        $portfolio->delete();

        return redirect()
            ->route('admin.portfolios.index')
            ->with('success', 'Karya berhasil dihapus.');
    }

    /**
     * Hapus satu foto tambahan tanpa meninggalkan halaman edit.
     */
    public function destroyImage(Portfolio $portfolio, PortfolioImage $image)
    {
        abort_unless($image->portfolio_id === $portfolio->id, 404);

        UploadHelper::deleteIfExists($image->image);
        $image->delete();

        return back()->with('success', 'Foto dihapus.');
    }

    private function validated(Request $request, bool $coverRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(array_keys(Portfolio::CATEGORIES))],
            'style' => ['nullable', 'string', 'max:100'],
            'client' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'floors' => ['nullable', 'integer', 'min:1', 'max:100'],
            'building_area' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'land_width' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'land_length' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'project_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer'],
            'cover_image' => UploadHelper::rules(required: $coverRequired),
            'images' => ['nullable', 'array', 'max:' . UploadHelper::MAX_BATCH],
            'images.*' => UploadHelper::rules(),
        ]);
    }

    private function storeExtraImages(Request $request, Portfolio $portfolio): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $urutan = (int) $portfolio->images()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $portfolio->images()->create([
                'image' => UploadHelper::image($file),
                'sort_order' => ++$urutan,
            ]);
        }
    }
}
