<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionProduct extends Model
{
    protected $fillable = [
        'pet_type_id',
        'name',
        'description',
        'price',
        'show_price',
        'images',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'show_price' => 'boolean',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }

   public function petType()
{
    return $this->belongsTo(PetTypeForNutrition::class, 'pet_type_id');
}

    public function getPrimaryImageUrlAttribute(): string
    {
        $images = $this->images ?? [];

        if (!empty($images)) {
            return asset('uploads/nutrition-products/' . $images[0]);
        }

        return asset('images/pet-placeholder.jpg');
    }

    public function getImageUrlsAttribute(): array
    {
        return collect($this->images ?? [])
            ->map(fn ($image) => asset('uploads/nutrition-products/' . $image))
            ->toArray();
    }
}
