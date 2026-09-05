<?php

namespace App\Models;

use App\Models\Concerns\PunyaTerjemahan;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use PunyaTerjemahan;

    protected $fillable = ['title', 'subtitle', 'icon', 'sort_order', 'title_en', 'subtitle_en'];
}