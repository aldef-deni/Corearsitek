<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Support\UploadHelper;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('admin.clients', [
            'clients' => Client::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

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

        Client::create($data);

        return back()->with('success', 'Klien berhasil ditambahkan.');
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

        return back()->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(Client $client)
    {
        UploadHelper::deleteIfExists($client->logo);
        $client->delete();

        return back()->with('success', 'Klien berhasil dihapus.');
    }
}
