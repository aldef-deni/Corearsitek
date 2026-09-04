<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Support\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'logo' => UploadHelper::rules(required: true),
        ]);

        $data['logo'] = UploadHelper::image($request->file('logo'));
        $data['is_active'] = $request->boolean('is_active');

        $client = Client::create($data);

        return $this->kembaliKeKlien($client, 'Klien berhasil ditambahkan.');
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'logo' => UploadHelper::rules(),
        ]);

        if ($request->hasFile('logo')) {
            UploadHelper::deleteIfExists($client->logo);
            $data['logo'] = UploadHelper::image($request->file('logo'));
        } else {
            unset($data['logo']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $client->update($data);

        return $this->kembaliKeKlien($client, 'Klien berhasil diperbarui.');
    }

    /**
     * Salin satu klien beserta logonya. Nama salinan diberi akhiran
     * "(salinan)" dan urutannya menyusul tepat di belakang aslinya,
     * jadi tinggal disunting seperlunya.
     */
    public function duplicate(Client $client)
    {
        $salinan = $client->replicate();
        $salinan->name = Str::limit($client->name, 240, '') . ' (salinan)';
        $salinan->sort_order = $client->sort_order + 1;
        $salinan->logo = UploadHelper::duplicateFile($client->logo) ?? $client->logo;
        $salinan->save();

        // Geser klien lain yang urutannya bertabrakan agar salinannya
        // benar-benar muncul persis di bawah aslinya.
        Client::where('id', '!=', $salinan->id)
            ->where('sort_order', '>=', $salinan->sort_order)
            ->increment('sort_order');

        return $this->kembaliKeKlien($salinan, 'Klien diduplikasi. Ubah nama dan logonya seperlunya.');
    }

    public function destroy(Client $client)
    {
        UploadHelper::deleteIfExists($client->logo);
        $client->delete();

        return $this->kembaliKeKlien(null, 'Klien berhasil dihapus.');
    }

    /**
     * Semua aksi klien dijalankan dari tengah halaman "Tentang CoreArsitek"
     * yang panjang. Tanpa penanda, halaman selalu kembali ke paling atas dan
     * pengguna harus menggulir ulang. Fragmen ini membuat peramban langsung
     * berhenti di kartu yang baru saja diubah.
     */
    private function kembaliKeKlien(?Client $client, string $pesan)
    {
        $fragment = $client ? 'klien-' . $client->id : 'klien';

        return back()->withFragment($fragment)->with('success', $pesan);
    }
}
