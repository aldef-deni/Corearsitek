<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Support\UploadHelper;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.testimonials', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string'],
            'avatar' => UploadHelper::rules(),
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = UploadHelper::image($request->file('avatar'));
        } else {
            unset($data['avatar']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $baru = Testimonial::create($data);

        return $this->kembaliKeBaris('testimoni', $baru, 'Testimoni berhasil ditambahkan.');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string'],
            'avatar' => UploadHelper::rules(),
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('avatar')) {
            UploadHelper::deleteIfExists($testimonial->avatar);
            $data['avatar'] = UploadHelper::image($request->file('avatar'));
        } else {
            unset($data['avatar']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $testimonial->update($data);

        return $this->kembaliKeBaris('testimoni', $testimonial, 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        UploadHelper::deleteIfExists($testimonial->avatar);
        $testimonial->delete();

        return $this->kembaliKeBaris('testimoni', null, 'Testimoni berhasil dihapus.');
    }
}
