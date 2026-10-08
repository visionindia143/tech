<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/blogs') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/vaccination-booking') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/diagnostic-booking') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach($blogs as $blog)
    <url>
        <loc>{{ route('blog.detail', $blog->slug) }}</loc>
        <lastmod>{{ $blog->updated_at->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach($pages as $page)
    <url>
        <loc>{{ url('/page/' . $page->slug) }}</loc>
        <lastmod>{{ $page->updated_at->toIso8601String() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach($cities as $city)
    <url>
        <loc>{{ route('city.detail', $city->slug) }}</loc>
        <lastmod>{{ $city->updated_at->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

     <!-- Service-City SEO Landing Pages -->
     @if(isset($serviceCitySeoPages))
         @foreach($serviceCitySeoPages as $page)
         <url>
             <loc>{{ $page->url }}</loc>
             <lastmod>{{ $page->updated_at->toIso8601String() }}</lastmod>
             <changefreq>weekly</changefreq>
             <priority>0.9</priority>
         </url>
         @endforeach
     @endif
</urlset>
