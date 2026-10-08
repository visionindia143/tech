<?php

namespace App\Http\Controllers;

use App\Models\{Blog, Page, Service, City, ServiceCitySeo};
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap()
    {
        $blogs = Blog::published()->orderBy('updated_at', 'desc')->get();
        $pages = Page::where('is_active', true)->get();
        $services = Service::active()->get();
        $cities = City::active()->get();
        
        // Get all active service-city SEO pages
        $serviceCitySeoPages = ServiceCitySeo::with(['service', 'city'])
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $content = view('seo.sitemap', compact('blogs', 'pages', 'services', 'cities', 'serviceCitySeoPages'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml') . "\n";
        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    public function llmTxt()
    {
        $siteName = \App\Models\Setting::get('site_name', 'PawCare');
        $desc = \App\Models\Setting::get('default_meta_description', 'Pet Shop & Pet Care Platform');

        $content = "# {$siteName}\n\n";
        $content .= "> {$desc}\n\n";
        $content .= "## Pages\n\n";

        $pages = Page::where('is_active', true)->get();
        foreach ($pages as $page) {
            $content .= "- [{$page->title}](" . url("/{$page->slug}") . ")\n";
        }

        $content .= "\n## Blog Posts\n\n";
        $blogs = Blog::published()->orderBy('created_at', 'desc')->limit(20)->get();
        foreach ($blogs as $blog) {
            $content .= "- [{$blog->title}](" . url("/blog/{$blog->slug}") . ")\n";
        }

        $content .= "\n## Services\n\n";
        $services = Service::active()->get();
        foreach ($services as $service) {
            $content .= "- {$service->title}: {$service->short_description}\n";
        }

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
