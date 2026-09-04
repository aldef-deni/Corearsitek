<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Support\UploadHelper;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function edit()
    {
        $contents = SiteContent::orderBy('id')->get();

        return view('admin.contents', compact('contents'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'contents' => ['required', 'array'],
            'contents.*.value' => ['nullable', 'string'],
            'contents.*.image' => ['nullable', 'image', 'max:5120'],
        ]);

        foreach ($data['contents'] as $id => $fields) {
            $item = SiteContent::findOrFail($id);

            if (! empty($fields['image']) && $fields['image'] instanceof \Illuminate\Http\UploadedFile) {
                UploadHelper::deleteIfExists($item->value);
                $item->value = UploadHelper::image($fields['image']);
            } elseif (array_key_exists('value', $fields)) {
                $item->value = $fields['value'];
            }

            $item->save();
        }

        return back()->with('success', 'Konten situs berhasil disimpan.');
    }
}