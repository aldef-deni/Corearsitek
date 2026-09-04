<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advantage extends Model
{
    /** Dua sisi yang ditampilkan berdampingan di halaman depan. */
    public const TYPES = [
        'rugi' => 'Kerugian Tanpa Jasa Arsitek',
        'untung' => 'Mengapa CoreArsitek',
    ];

    protected $fillable = ['type', 'text', 'sort_order'];

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
