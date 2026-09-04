<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->get();

        return view('admin.services', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $baru = Service::create($data);

        return $this->kembaliKeBaris('layanan', $baru, 'Layanan berhasil ditambahkan.');
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $service->update($data);

        return $this->kembaliKeBaris('layanan', $service, 'Layanan berhasil diperbarui.');
    }

    /**
     * Salin satu layanan beserta seluruh isinya, tinggal disunting.
     */
    public function duplicate(Service $service)
    {
        $salinan = $this->duplikatBaris($service, 'title');

        return $this->kembaliKeBaris('layanan', $salinan, 'Layanan diduplikasi. Ubah judul dan keterangannya seperlunya.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return $this->kembaliKeBaris('layanan', null, 'Layanan berhasil dihapus.');
    }
}