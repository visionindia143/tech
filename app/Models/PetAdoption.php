<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PetAdoption extends Model
{
    protected $fillable = [
        'name', 'slug', 'breed', 'age', 'gender', 'size', 'color', 'pet_type',
        'short_description', 'description', 'images', 'adoption_fee',
        'is_vaccinated', 'is_neutered', 'status', 'is_active', 'is_featured', 'order',
    ];

    protected $casts = [
        'images'       => 'array',
        'is_vaccinated'=> 'boolean',
        'is_neutered'  => 'boolean',
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'adoption_fee' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(AdoptionBooking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order')->orderBy('created_at', 'desc');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function getFirstImageUrlAttribute(): string
    {
        $images = $this->images ?? [];
        if (count($images)) {
            return asset('uploads/adoptions/' . $images[0]);
        }
        return asset('images/pet-placeholder.jpg');
    }

    public function getImageUrlsAttribute(): array
    {
        return collect($this->images ?? [])->map(fn($img) => asset('uploads/adoptions/' . $img))->toArray();
    }

    public static function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }
        return $slug;
    }
}
