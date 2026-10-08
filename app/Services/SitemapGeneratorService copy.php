<?php

namespace App\Services;

use App\Models\ServiceCitySeo;
use App\Models\Service;
use App\Models\City;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SitemapGeneratorService
{
    /**
     * Generate all service-city sitemap entries
     */
    public static function getServiceCitySitemapEntries(): Collection
    {
        return ServiceCitySeo::with(['service', 'city'])
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function (ServiceCitySeo $page) {
                return [
                    'url' => $page->url,
                    'lastmod' => $page->updated_at->toIso8601String(),
                    'changefreq' => 'weekly',
                    'priority' => 0.9,
                    'title' => $page->seo_title,
                ];
            });
    }

    /**
     * Get active combinations count
     */
    public static function getActiveServiceCityCombinations(): int
    {
        return ServiceCitySeo::where('is_active', true)->count();
    }

    /**
     * Get potential combinations (all services × all cities)
     */
    public static function getPotentialCombinations(): int
    {
        $activeServices = Service::where('is_active', true)->count();
        $activeCities = City::where('is_active', true)->count();

        return $activeServices * $activeCities;
    }

    /**
     * Get percentage of coverage
     */
    public static function getCoveragePercentage(): float
    {
        $potential = self::getPotentialCombinations();

        if ($potential === 0) {
            return 0;
        }

        $active = self::getActiveServiceCityCombinations();
        return round(($active / $potential) * 100, 2);
    }

    /**
     * Get combinations that need SEO pages
     */
    public static function getMissingCombinations(): Collection
    {
        $allServices = Service::where('is_active', true)->get();
        $allCities = City::where('is_active', true)->get();

        $missing = collect();

        foreach ($allServices as $service) {

            foreach ($allCities as $city) {

                $exists = ServiceCitySeo::withTrashed()
                    ->where('service_id', $service->id)
                    ->where('city_id', $city->id)
                    ->exists();

                if (!$exists) {

                    $missing->push([
                        'service_id' => $service->id,
                        'service_name' => $service->title,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'url_slug' => $service->slug . '-in-' . $city->slug,
                    ]);
                }
            }
        }

        return $missing;
    }

    /**
     * Bulk create missing SEO pages with default content
     */
    public static function createMissingPages(bool $autoApprove = false): array
    {
        $missing = self::getMissingCombinations();
        $created = 0;
        $failed = 0;

        foreach ($missing as $combo) {
            try {
                // Check if combination already exists (handles race condition)
                $existing = ServiceCitySeo::withTrashed()
                    ->where('service_id', $combo['service_id'])
                    ->where('city_id', $combo['city_id'])
                    ->first();

                if (!$existing) {
                    ServiceCitySeo::create([
                        'service_id' => $combo['service_id'],
                        'city_id' => $combo['city_id'],
                        'is_active' => $autoApprove,
                        'priority' => 0,
                        'seo_title' => null,
                        'meta_description' => null,
                        'meta_keywords' => null,
                        'custom_content' => null,
                        'faq_content' => null,
                    ]);
                    $created++;
                }
            } catch (\Exception $e) {
              
                $failed++;
                // Log the error for debugging
                Log::error('Failed to create service-city SEO page', [
                    'combo' => $combo,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'created' => $created,
            'failed' => $failed,
            'total' => $missing->count(),
        ];
    }

    /**
     * Get statistics
     */
    public static function getStatistics(): array
    {
        $activeServices = Service::where('is_active', true)->count();
        $activeCities = City::where('is_active', true)->count();
        $activeCombinations = self::getActiveServiceCityCombinations();
        $potentialCombinations = self::getPotentialCombinations();

        return [
            'active_services' => $activeServices,
            'active_cities' => $activeCities,
            'active_combinations' => $activeCombinations,
            'potential_combinations' => $potentialCombinations,
            'coverage_percentage' => self::getCoveragePercentage(),
            'missing_combinations' => $potentialCombinations - $activeCombinations,
        ];
    }

    /**
     * Export statistics as array
     */
    public static function exportStats(): array
    {
        $stats = self::getStatistics();

        return [
            'report_generated_at' => now()->toIso8601String(),
            'statistics' => $stats,
            'active_urls' => self::getServiceCitySitemapEntries()->toArray(),
        ];
    }
}
