<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\City;
use App\Models\ServiceCitySeo;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Show service detail page
     */
    public function show(string $slug)
    {
        $service = Service::active()->where('slug', $slug)->firstOrFail();
        $related = Service::active()->where('id', '!=', $service->id)->limit(3)->get();

        return view('frontend.service-detail', compact('service', 'related'));
    }

    /**
     * Show service-city SEO landing page
     * URL: /pet-grooming-in-mohali
     */

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
    public function showServiceCity(string $serviceSlug, string $citySlug)
    {
        // Get the service by slug
        $service = Service::where('slug', $serviceSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $city = City::where('slug', $citySlug)
            ->where('is_active', true)
            ->first();

        if (!$city) {
            $city = City::where('is_active', true)
                ->get()
                ->first(function ($city) use ($citySlug) {
                    return Str::slug($city->name) === $citySlug;
                });
        }

        abort_if(!$city, 404);

        // Get the service-city SEO data
        $page = ServiceCitySeo::with(['service', 'city'])
            ->where('service_id', $service->id)
            ->where('city_id', $city->id)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related services in the same city
        $relatedServices = ServiceCitySeo::with(['service'])
            ->where('city_id', $city->id)
            ->where('service_id', '!=', $service->id)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->limit(4)
            ->get();

        // Get related cities for the same service
        $relatedCities = ServiceCitySeo::with(['city'])
            ->where('service_id', $service->id)
            ->where('city_id', '!=', $city->id)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->limit(4)
            ->get();

        return view('frontend.service-city-seo', compact(
            'page',
            'service',
            'city',
            'relatedServices',
            'relatedCities'
        ));
    }
}
