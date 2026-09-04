<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    /** Kategori karya, dipakai sebagai tab penyaring di halaman portofolio. */
    public const CATEGORIES = [
        'desain-rumah' => 'Desain Rumah',
        'desain-villa' => 'Desain Villa',
        'desain-interior' => 'Desain Interior',
        'desain-bangunan-lain' => 'Desain Bangunan Lain',
        'hasil-konstruksi' => 'Hasil Konstruksi',
    ];

    /** Saran gaya desain; kolomnya tetap bebas diisi apa saja. */
    public const STYLE_SUGGESTIONS = [
        'Modern', 'Klasik', 'Mediteran', 'Industrial',
        'Villa Bali', 'Skandinavia', 'American', 'Kontemporer', 'Tropis',
    ];

    protected $fillable = [
        'title',
        'slug',
        'category',
        'style',
        'client',
        'location',
        'floors',
        'building_area',
        'land_width',
        'land_length',
        'project_date',
        'description',
        'cover_image',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'project_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'building_area' => 'decimal:2',
        'land_width' => 'decimal:2',
        'land_length' => 'decimal:2',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(PortfolioImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeCategory($query, ?string $category)
    {
        return $category && isset(self::CATEGORIES[$category])
            ? $query->where('category', $category)
            : $query;
    }

    /**
     * Urutan tampil: kolom Urutan lebih dulu, lalu karya terbaru di atas.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')
            ->orderByDesc('project_date')
            ->orderByDesc('id');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /**
     * Ringkasan spesifikasi untuk ditampilkan di kartu, hanya yang terisi.
     */
    public function specs(): array
    {
        $specs = [];

        if ($this->building_area) {
            $specs[] = 'LB ' . self::number($this->building_area) . ' m²';
        }

        if ($this->land_width && $this->land_length) {
            $specs[] = 'Lebar ' . self::number($this->land_width)
                . ' m × Panjang ' . self::number($this->land_length) . ' m';
        }

        if ($this->floors) {
            $specs[] = $this->floors . ' lantai';
        }

        return $specs;
    }

    /** Buang angka nol di belakang koma supaya "305.00" tampil sebagai "305". */
    private static function number($value): string
    {
        return rtrim(rtrim(number_format((float) $value, 2, ',', '.'), '0'), ',');
    }

    /**
     * Slug unik dari judul; angka pembeda ditambahkan bila sudah terpakai.
     */
    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'karya';
        $slug = $base;
        $n = 2;

        while (self::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
