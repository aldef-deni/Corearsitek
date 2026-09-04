<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Support\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    public function index()
    {
        // Dikelompokkan per halaman supaya jelas banner mana milik halaman mana.
        $banners = Banner::orderBy('sort_order')->orderBy('id')->get()->groupBy('page');

        return view('admin.banners', compact('banners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'page' => ['required', 'string', Rule::in(array_keys(Banner::PAGES))],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'badge_text' => ['nullable', 'string', 'max:255'],
            'image' => UploadHelper::rules(required: true),
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['image'] = UploadHelper::image($request->file('image'));
        $data['is_active'] = $request->boolean('is_active');

        $baru = Banner::create($data);

        return $this->kembaliKeBaris('banner', $baru, 'Banner berhasil ditambahkan.');
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'page' => ['required', 'string', Rule::in(array_keys(Banner::PAGES))],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'badge_text' => ['nullable', 'string', 'max:255'],
            'image' => UploadHelper::rules(),
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('image')) {
            UploadHelper::deleteIfExists($banner->image);
            $data['image'] = UploadHelper::image($request->file('image'));
        } else {
            unset($data['image']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $banner->update($data);

        return $this->kembaliKeBaris('banner', $banner, 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        UploadHelper::deleteIfExists($banner->image);
        $banner->delete();

        return $this->kembaliKeBaris('banner', null, 'Banner berhasil dihapus.');
    }
}
