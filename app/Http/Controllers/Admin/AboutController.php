<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\SiteContent;
use App\Support\UploadHelper;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Kolom teks halaman Tentang. Kuncinya sama dengan yang ada di
     * tabel site_contents.
     */
    private const TEXT_KEYS = [
        'about_title',
        'about_tagline',
        'about_text',
        'about_vision',
        'about_mission',
        'about_profile_name',
        'about_profile_role',
        'about_profile_quote',
        'about_profile_bio',
        'about_profile_skills',
    ];

    public function edit()
    {
        return view('admin.about', [
            'contents' => SiteContent::pluck('value', 'key')->toArray(),
            'clients' => Client::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_tagline' => ['nullable', 'string', 'max:500'],
            'about_text' => ['nullable', 'string', 'max:5000'],
            'about_vision' => ['nullable', 'string', 'max:2000'],
            'about_mission' => ['nullable', 'string', 'max:3000'],
            'about_profile_name' => ['nullable', 'string', 'max:255'],
            'about_profile_role' => ['nullable', 'string', 'max:255'],
            'about_profile_quote' => ['nullable', 'string', 'max:1000'],
            'about_profile_bio' => ['nullable', 'string', 'max:5000'],
            'about_profile_skills' => ['nullable', 'string', 'max:2000'],
            'about_profile_image' => UploadHelper::rules(),
        ]);

        foreach (self::TEXT_KEYS as $key) {
            SiteContent::where('key', $key)->update(['value' => $data[$key] ?? '']);
        }

        if ($request->hasFile('about_profile_image')) {
            $lama = SiteContent::where('key', 'about_profile_image')->value('value');
            UploadHelper::deleteIfExists($lama);

            SiteContent::where('key', 'about_profile_image')
                ->update(['value' => UploadHelper::image($request->file('about_profile_image'))]);
        }

        return back()->with('success', 'Halaman Tentang berhasil disimpan.');
    }

    /**
     * Hapus foto profil tanpa mengubah isian lain di halaman ini.
     */
    public function removeProfileImage()
    {
        $baris = SiteContent::where('key', 'about_profile_image')->first();

        if ($baris) {
            UploadHelper::deleteIfExists($baris->value);
            $baris->update(['value' => '']);
        }

        return back()->with('success', 'Foto profil dihapus.');
    }
}
