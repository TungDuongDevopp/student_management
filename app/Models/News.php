<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'target_audience',
        'category',
        'is_published'
    ];
}
