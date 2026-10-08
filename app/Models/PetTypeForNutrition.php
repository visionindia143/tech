<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetTypeForNutrition extends Model
{
    protected $table = 'pet_type_for_nutrition';

    protected $fillable = ['name', 'icon', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->orderBy('sort_order')
                     ->orderBy('name');
    }
protected static function booted()
{
    static::deleting(function ($petType) {
        $petType->nutritionProducts()->delete();
    });
}
    public function nutritionProducts()
    {
        return $this->hasMany(NutritionProduct::class, 'pet_type_id')
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
