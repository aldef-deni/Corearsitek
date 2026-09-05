<?php

namespace App\Models;

use App\Models\Concerns\PunyaTerjemahan;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use PunyaTerjemahan;

    protected $fillable = [
        'name',
        'role',
        'quote',
        'avatar',
        'is_active',
        'sort_order',
        'role_en',
        'quote_en',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
