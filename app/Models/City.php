<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class City extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    /**
     * Boot the model - auto-generate slug
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function (self $model) {
            if ($model->isDirty('name') && !$model->isDirty('slug')) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function bookings()
    {
        return $this->hasMany(VaccinationBooking::class);
    }

    public function testBookings()
    {
        return $this->hasMany(TestBooking::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('uploads/cities/' . $this->image) : '';
    }

    /**
     * Get all service-city SEO pages for this city
     */
    public function citySeoPages(): HasMany
    {
        return $this->hasMany(ServiceCitySeo::class);
    }

    /**
     * Get active service-city SEO pages
     */
    public function activeCitySeoPages(): HasMany
    {
        return $this->citySeoPages()->where('is_active', true);
    }

    /**
     * Get all services for this city with SEO data
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_city_seo')
            ->withPivot('id', 'seo_title', 'meta_description', 'meta_keywords', 'custom_content', 'faq_content', 'is_active', 'priority')
            ->withTimestamps()
            ->wherePivot('is_active', true)
            ->orderByPivot('priority', 'desc');
    }

    /**
     * Get route key (slug)
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
