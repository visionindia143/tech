<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCitySeo extends Model
{
    use SoftDeletes;

    protected $table = 'service_city_seo';

    protected $fillable = [
        'service_id',
        'city_id',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'custom_content',
        'faq_content',
        'is_active',
        'priority',
        'custom_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'faq_content' => 'json',
        'priority' => 'integer',
    ];

    /**
     * Get the service for this SEO page
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the city for this SEO page
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Scope to active pages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Generate URL slug for this service-city combination
     * Returns format like: pet-grooming-in-mohali
     */
    public function getSlugAttribute(): string
    {
        return $this->service->slug . '-in-' . $this->city->slug;
    }



    public function showCustomSeo($customSlug)
    {
        $seoPage = ServiceCitySeo::with(['service', 'city'])
            ->where('custom_url', $customSlug)
            ->where('is_active', 1)
            ->firstOrFail();

        return $this->showServiceCity(
            $seoPage->service->slug,
            $seoPage->city->slug
        );
    }

    /**
     * Generate full URL for this page
     */
    public function getUrlAttribute(): string
    {
        if ($this->custom_url) {
            return route('services.custom', [
                'customSlug' => $this->custom_url
            ]);
        }

        return route('services.city', [
            'serviceSlug' => $this->service->slug,
            'citySlug' => $this->city->slug,
        ]);
    }

    /**
     * Get SEO title or fallback to default
     */
    public function getSeoTitleAttribute($value): string
    {
        return $value ?: "{$this->service->title} in {$this->city->name}";
    }

    /**
     * Get meta description or fallback to default
     */
    public function getMetaDescriptionAttribute($value): string
    {
        return $value ?: "Find professional {$this->service->title} services in {$this->city->name}. " .
            "Book your appointment today with our experienced team.";
    }

    /**
     * Get FAQ content as array of objects
     */
    public function getFaqAttribute(): array
    {
        if (is_null($this->faq_content)) {
            return [];
        }

        $faqs = is_array($this->faq_content) ? $this->faq_content : json_decode($this->faq_content, true);

        return array_map(function ($faq) {
            return [
                'question' => $faq['question'] ?? '',
                'answer' => $faq['answer'] ?? '',
            ];
        }, $faqs ?? []);
    }

    /**
     * Scope to paginated results
     */
    public function scopePaginated($query, int $perPage = 15)
    {
        return $query->active()->with(['service', 'city'])->paginate($perPage);
    }
}
