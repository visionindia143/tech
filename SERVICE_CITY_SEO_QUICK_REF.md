# Service-City SEO - Quick Reference

## 🚀 Quick Start

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Generate Sample Pages
```bash
php artisan seo:generate-pages --auto-approve
```

### 3. Access Admin Panel
```
https://yoursite.com/admin/service-city-seo
```

### 4. Test Frontend
```
https://yoursite.com/pet-grooming-in-mohali
```

---

## 📁 File Locations

| Component | Path |
|-----------|------|
| Model | `app/Models/ServiceCitySeo.php` |
| Frontend Controller | `app/Http/Controllers/Frontend/ServiceController.php` |
| Admin Controller | `app/Http/Controllers/Admin/ServiceCitySeoController.php` |
| Schema Service | `app/Services/SchemaMarkupService.php` |
| Sitemap Service | `app/Services/SitemapGeneratorService.php` |
| Blade Template | `resources/views/frontend/service-city-seo.blade.php` |
| CLI Command | `app/Console/Commands/GenerateServiceCitySeoPages.php` |
| Migrations | `database/migrations/2024_01_01_00005*.php` |
| Seeder | `database/seeders/ServiceCitySeoSeeder.php` |

---

## 🔧 CLI Commands

```bash
# Show stats
php artisan seo:generate-pages --stats

# Generate all
php artisan seo:generate-pages

# Auto-approve
php artisan seo:generate-pages --auto-approve

# For service
php artisan seo:generate-pages --service=1

# For city
php artisan seo:generate-pages --city=1

# Single combo
php artisan seo:generate-pages --service=1 --city=1
```

---

## 💾 Database Table

**service_city_seo**
- `id` (PK)
- `service_id` (FK → services.id)
- `city_id` (FK → cities.id)
- `seo_title` - Page title (auto-generated)
- `meta_description` - Meta description
- `meta_keywords` - Keywords
- `custom_content` - Rich HTML content
- `faq_content` - JSON array of FAQs
- `is_active` - Boolean status
- `priority` - Integer (0-100)

---

## 🔗 URL Structure

```
/{serviceSlug}-in-{citySlug}

Examples:
/pet-grooming-in-mohali
/pet-boarding-in-chandigarh
/pet-vaccination-in-panchkula
```

---

## 📊 Schema.org Markup

Included automatically:
- ✅ Breadcrumb Schema
- ✅ LocalBusiness Schema
- ✅ FAQ Schema (if FAQs exist)
- ✅ Organization Schema
- ✅ AggregateRating Schema

---

## 🎯 Model Relationships

```php
// Service → ServiceCitySeo → City
$service->citySeoPages()        // All pages
$service->activeCitySeoPages()  // Only active
$service->cities()              // All cities with pages

// City → ServiceCitySeo → Service
$city->citySeoPages()           // All pages
$city->activeCitySeoPages()     // Only active
$city->services()               // All services with pages

// ServiceCitySeo
$page->service                  // Service model
$page->city                     // City model
$page->faq                      // FAQ array
$page->url                      // Generated URL
```

---

## 🌐 Routes

```php
// Frontend route
Route::get('/{serviceSlug}-in-{citySlug}', 
    [FrontendServiceController::class, 'showServiceCity'])
    ->name('services.city');

// Admin routes (RESTful)
admin/service-city-seo              → index
admin/service-city-seo/create       → create
admin/service-city-seo              → store
admin/service-city-seo/{id}         → show
admin/service-city-seo/{id}/edit    → edit
admin/service-city-seo/{id}         → update
admin/service-city-seo/{id}         → destroy
```

---

## 💡 Common Tasks

### Create Page Programmatically
```php
ServiceCitySeo::create([
    'service_id' => 1,
    'city_id' => 1,
    'seo_title' => 'Pet Grooming in Mohali',
    'meta_description' => '...',
    'is_active' => true,
    'priority' => 10,
]);
```

### Get Active Pages
```php
$pages = ServiceCitySeo::where('is_active', true)
    ->with(['service', 'city'])
    ->get();
```

### Generate Schema
```php
use App\Services\SchemaMarkupService;

$breadcrumb = SchemaMarkupService::breadcrumbSchema($page);
$business = SchemaMarkupService::localBusinessSchema($page);
$faq = SchemaMarkupService::faqSchema($page);
```

### Get Statistics
```php
use App\Services\SitemapGeneratorService;

$stats = SitemapGeneratorService::getStatistics();
// ['active_services', 'active_cities', 'active_combinations', etc]
```

---

## 🧪 Testing URLs

```bash
# Test in browser
http://localhost/pet-grooming-in-mohali

# Check source for schemas
curl -s http://localhost/pet-grooming-in-mohali | grep -i schema

# Validate sitemap
http://localhost/sitemap.xml

# API for stats (if implemented)
http://localhost/admin/service-city-seo/stats/export
```

---

## ⚡ Performance Tips

1. **Pagination**: Lists paginate 20 items
2. **Eager Loading**: Controller uses `with(['service', 'city'])`
3. **Indexes**: Table has indexes on active status, service, city, priority
4. **Caching**: Consider caching stats in production
5. **Soft Deletes**: Use soft deletes, not hard deletes

---

## 🐛 Debugging

### Enable Query Logging
```php
DB::enableQueryLog();
// ... do something ...
dd(DB::getQueryLog());
```

### Check Relationships
```php
$page = ServiceCitySeo::find(1);
dd($page->load(['service', 'city'])->toArray());
```

### Validate Schema
```
https://search.google.com/test/rich-results
```

---

## 📝 Future Enhancements

- [ ] Add image upload per service-city combo
- [ ] Add review/rating system
- [ ] Add A/B testing support
- [ ] Add conversion tracking
- [ ] Add auto-generate with AI (ChatGPT)
- [ ] Add multi-language support
- [ ] Add import/export functionality
- [ ] Add scheduling for page activation

---

## 🆘 Troubleshooting

| Issue | Solution |
|-------|----------|
| 404 on URL | Check service/city active status, verify combination exists |
| Schemas not showing | Validate in Google Rich Results Test, wait 24h for indexing |
| Slow admin page | Check query count, enable pagination |
| Duplicate content | Add unique custom_content per combination |
| Slug not generating | Check Service/City model for slug generation code |

---

## 📞 Support

For issues or questions:
1. Check IMPLEMENTATION_GUIDE.md
2. Review model relationships
3. Check browser console for errors
4. Review Laravel logs: `storage/logs/laravel.log`
5. Validate schema: Google Rich Results Test

---

Last Updated: 2024-06-01
