<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaccinationType extends Model
{
    protected $fillable = ['name', 'description', 'price', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function bookings()
    {
        return $this->hasMany(VaccinationBooking::class);
    }
}
