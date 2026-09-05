<?php

namespace App\Models;

use App\Models\Concerns\PunyaTerjemahan;
use Illuminate\Database\Eloquent\Model;

class ProcessStep extends Model
{
    use PunyaTerjemahan;

    protected $fillable = [
        'icon',
        'title',
        'description',
        'sort_order',
        'title_en',
        'description_en',
    ];
}
