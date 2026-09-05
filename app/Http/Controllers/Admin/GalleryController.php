<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Support\UploadHelper;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::orderBy('sort_order')->get();

        return view('admin.galleries', compact('galleries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'image' => UploadHelper::rules(required: true),
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['image'] = UploadHelper::image($request->file('image'));

        $baru = Gallery::create($data);

        return $this->kembaliKeBaris('galeri', $baru, 'Galeri berhasil ditambahkan.');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'image' => UploadHelper::rules(),
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('image')) {
            UploadHelper::deleteIfExists($gallery->image);
            $data['image'] = UploadHelper::image($request->file('image'));
        }

        $gallery->update($data);

        return $this->kembaliKeBaris('galeri', $gallery, 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        UploadHelper::deleteIfExists($gallery->image);
        $gallery->delete();

        return $this->kembaliKeBaris('galeri', null, 'Galeri berhasil dihapus.');
    }
}