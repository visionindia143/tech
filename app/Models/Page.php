<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'image', 'youtube_video_url',
        'meta_title', 'meta_description', 'meta_keywords', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('uploads/pages/' . $this->image) : '';
    }
}
