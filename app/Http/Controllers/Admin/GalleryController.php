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
            'image' => UploadHelper::rules(required: true),
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['image'] = UploadHelper::image($request->file('image'));

        Gallery::create($data);

        return back()->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => UploadHelper::rules(),
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('image')) {
            UploadHelper::deleteIfExists($gallery->image);
            $data['image'] = UploadHelper::image($request->file('image'));
        }

        $gallery->update($data);

        return back()->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        UploadHelper::deleteIfExists($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Galeri berhasil dihapus.');
    }
}