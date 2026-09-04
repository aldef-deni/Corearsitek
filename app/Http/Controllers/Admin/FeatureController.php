<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('sort_order')->get();

        return view('admin.features', compact('features'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $baru = Feature::create($data);

        return $this->kembaliKeBaris('fitur', $baru, 'Keunggulan berhasil ditambahkan.');
    }

    public function update(Request $request, Feature $feature)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $feature->update($data);

        return $this->kembaliKeBaris('fitur', $feature, 'Keunggulan berhasil diperbarui.');
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();

        return $this->kembaliKeBaris('fitur', null, 'Keunggulan berhasil dihapus.');
    }
}