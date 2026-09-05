<?php

namespace App\Models;

use App\Models\Concerns\PunyaTerjemahan;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use PunyaTerjemahan;

    protected $fillable = ['icon', 'label', 'sort_order', 'label_en'];
}