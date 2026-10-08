<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceCitySeo;
use App\Models\Service;
use App\Models\City;
use App\Services\SitemapGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceCitySeoController extends Controller
{
    /**
     * Display a listing of SEO pages
     */
    public function index(Request $request): View
    {
        $query = ServiceCitySeo::with(['service', 'city']);

        // Filter by service
        if ($request->filled('service')) {
            $query->where('service_id', $request->service);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city_id', $request->city);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('service', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhereHas('city', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $seoPages = $query->paginate(20);
        $services = Service::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();

        // Statistics
        $stats = SitemapGeneratorService::getStatistics();

        return view('admin.service-city-seo.index', compact('seoPages', 'services', 'cities', 'stats'));
    }

    /**
     * Show the form for creating a new SEO page
     */
    public function create(): View
    {
        $services = Service::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();
        $existingCombinations = ServiceCitySeo::pluck('city_id', 'service_id')->toArray();

        return view('admin.service-city-seo.create', compact('services', 'cities', 'existingCombinations'));
    }

    /**
     * Store a newly created SEO page
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'seo_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'custom_content' => 'nullable|string',
            'faq_content' => 'nullable|json',
            'custom_url' => 'nullable|string|max:255|alpha_dash|unique:service_city_seo,custom_url',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:100',
        ]);

        // Check for existing combination
        $existing = ServiceCitySeo::where('service_id', $validated['service_id'])
            ->where('city_id', $validated['city_id'])
            ->first();

        if ($existing) {
            return redirect()->back()->withErrors([
                'combination' => 'This service-city combination already exists.'
            ]);
        }

        ServiceCitySeo::create($validated);

        return redirect()->route('admin.service-city-seo.index')
            ->with('success', 'SEO page created successfully!');
    }

    /**
     * Display the specified SEO page
     */
    public function show(ServiceCitySeo $serviceCitySeo): View
    {
        $serviceCitySeo->load(['service', 'city']);

        return view('admin.service-city-seo.show', compact('serviceCitySeo'));
    }

    /**
     * Show the form for editing the specified SEO page
     */
    public function edit(ServiceCitySeo $serviceCitySeo): View
    {
        $serviceCitySeo->load(['service', 'city']);
        $services = Service::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();

        return view('admin.service-city-seo.edit', compact('serviceCitySeo', 'services', 'cities'));
    }

    /**
     * Update the specified SEO page
     */
    public function update(Request $request, ServiceCitySeo $serviceCitySeo): RedirectResponse
    {
        $validated = $request->validate([
            'seo_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'custom_content' => 'nullable|string',
            'faq_content' => 'nullable|json',
            'custom_url' => 'nullable|string|max:255|alpha_dash|unique:service_city_seo,custom_url,' . $serviceCitySeo->id,
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:100',
        ]);

        $serviceCitySeo->update($validated);

        return redirect()->route('admin.service-city-seo.show', $serviceCitySeo)
            ->with('success', 'SEO page updated successfully!');
    }

    /**
     * Delete the specified SEO page
     */
    public function destroy(ServiceCitySeo $serviceCitySeo): RedirectResponse
    {
        $serviceCitySeo->forceDelete();

        return redirect()->route('admin.service-city-seo.index')
            ->with('success', 'SEO page deleted successfully!');
    }

    /**
     * Bulk generate missing pages
     */
    public function bulkGenerate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'auto_approve' => 'boolean',
        ]);

        $result = SitemapGeneratorService::createMissingPages($validated['auto_approve'] ?? false);

        return redirect()->route('admin.service-city-seo.index')
            ->with('success', "Generated {$result['created']} new SEO pages. {$result['failed']} failed.");
    }

    

    /**
     * Toggle active status
     */
    public function toggleActive(ServiceCitySeo $serviceCitySeo): RedirectResponse
    {
        $serviceCitySeo->update(['is_active' => !$serviceCitySeo->is_active]);

        return redirect()->back()
            ->with('success', 'Page status updated!');
    }

    /**
     * Export statistics as JSON
     */
    public function exportStats()
    {
        $stats = SitemapGeneratorService::exportStats();

        return response()->json($stats);
    }
}
