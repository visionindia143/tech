<?php

namespace App\Models;

use App\Models\AppointmentBooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'title', 'slug', 'icon', 'image', 'short_description',
        'description', 'price','button_label','button_url', 'order', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    /**
     * Boot the model - auto-generate slug
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });

        static::updating(function (self $model) {
            if ($model->isDirty('title') && !$model->isDirty('slug')) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('uploads/services/' . $this->image) : '';
    }

    public function appointments()
    {
        return $this->hasMany(AppointmentBooking::class);
    }

    /**
     * Get all service-city SEO pages for this service
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
     * Get all cities for this service with SEO data
     */
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'service_city_seo')
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
