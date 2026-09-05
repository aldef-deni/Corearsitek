<?php

namespace App\Models;

use App\Models\Concerns\PunyaTerjemahan;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use PunyaTerjemahan;

    /**
     * Halaman yang bisa punya banner sendiri.
     * Kunci disimpan di kolom `page`, nilainya dipakai sebagai label di dashboard.
     */
    public const PAGES = [
        'home' => 'Beranda',
        'portfolio' => 'Portofolio',
        'gallery' => 'Galeri',
        'services' => 'Layanan',
        'about' => 'Tentang CoreArsitek',
        'contact' => 'Kontak',
    ];

    protected $fillable = [
        'page',
        'title',
        'subtitle',
        'badge_text',
        'image',
        'button_text',
        'button_url',
        'is_active',
        'sort_order',
        'title_en',
        'subtitle_en',
        'badge_text_en',
        'button_text_en',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForPage($query, string $page)
    {
        return $query->where('page', $page);
    }

    public function pageLabel(): string
    {
        return self::PAGES[$this->page] ?? $this->page;
    }
}
