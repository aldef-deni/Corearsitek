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
            'label_en' => ['nullable', 'string', 'max:255'],
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
            'label_en' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $feature->update($data);

        return $this->kembaliKeBaris('fitur', $feature, 'Keunggulan berhasil diperbarui.');
    }

    /**
     * Salin satu keunggulan agar penambahan berikutnya tinggal menyunting
     * label dan ikonnya.
     */
    public function duplicate(Feature $feature)
    {
        $salinan = $this->duplikatBaris($feature, 'label');

        return $this->kembaliKeBaris('fitur', $salinan, 'Keunggulan diduplikasi. Ubah label dan ikonnya seperlunya.');
    }

    /**
     * Simpan seluruh baris di halaman ini sekaligus. Berkas gambar tidak ikut
     * — penggantian foto tetap lewat tombol Simpan pada barisnya sendiri.
     */
    public function saveAll(Request $request)
    {
        $berubah = $this->simpanBanyak($request, Feature::class, [
            'label' => ['required', 'string', 'max:255'],
            'label_en' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        return $this->kembaliKeBaris('fitur', null, $berubah
            ? $berubah . ' baris Keunggulan disimpan.'
            : 'Tidak ada perubahan untuk disimpan.');
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();

        return $this->kembaliKeBaris('fitur', null, 'Keunggulan berhasil dihapus.');
    }
}