<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content', 'image',
        'meta_title', 'meta_description', 'meta_keywords',
        'status', 'views', 'show_in_slider'
    ];

    protected $casts = [
        'show_in_slider' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'publish');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('uploads/blogs/' . $this->image) : asset('images/blog-default.jpg');
    }

    public function getExcerptAttribute()
    {
        return \Str::limit(strip_tags($this->content), 150);
    }

    public function relatedPosts($limit = 3)
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
