<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'image', 'mobile_image', 'button_text',
        'button_link', 'order', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('uploads/sliders/' . $this->image) : '';
    }

    public function getMobileImageUrlAttribute()
    {
        return $this->mobile_image ? asset('uploads/sliders/' . $this->mobile_image) : '';
    }
}
