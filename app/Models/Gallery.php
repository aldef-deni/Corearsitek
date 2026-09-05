<?php

namespace App\Models;

use App\Models\Concerns\PunyaTerjemahan;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use PunyaTerjemahan;

    protected $fillable = ['title', 'image', 'description', 'sort_order', 'title_en', 'description_en'];
}