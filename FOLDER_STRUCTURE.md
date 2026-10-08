# Recommended Laravel 11 Folder Structure for Service-City SEO

## 📂 Complete Project Structure

```
pawshop/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── GenerateServiceCitySeoPages.php          ✨ NEW
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Frontend/
│   │   │   │   ├── ServiceController.php                🔄 UPDATED
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── AdoptionController.php
│   │   │   │   └── ...
│   │   │   └── Admin/
│   │   │       ├── ServiceCitySeoController.php         ✨ NEW
│   │   │       ├── ServiceController.php
│   │   │       ├── CityController.php
│   │   │       └── ...
│   │   └── Requests/
│   │       └── ServiceCitySeoRequest.php (Optional)
│   ├── Models/
│   │   ├── Service.php                                  🔄 UPDATED
│   │   ├── City.php                                     🔄 UPDATED
│   │   ├── ServiceCitySeo.php                           ✨ NEW
│   │   ├── User.php
│   │   └── ...
│   ├── Services/
│   │   ├── SchemaMarkupService.php                      ✨ NEW
│   │   ├── SitemapGeneratorService.php                  ✨ NEW
│   │   ├── MailService.php
│   │   ├── MSG91Service.php
│   │   └── ...
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000050_create_service_city_seo_table.php    ✨ NEW
│   │   ├── 2024_01_01_000051_add_slug_to_services_table.php       ✨ NEW
│   │   ├── 2024_01_01_000052_add_slug_to_cities_table.php         ✨ NEW
│   │   └── ...
│   └── seeders/
│       ├── ServiceCitySeoSeeder.php                     ✨ NEW
│       └── ...
│
├── resources/
│   ├── views/
│   │   ├── frontend/
│   │   │   ├── service-city-seo.blade.php               ✨ NEW
│   │   │   ├── service-detail.blade.php
│   │   │   ├── city-detail.blade.php
│   │   │   └── ...
│   │   ├── admin/
│   │   │   ├── service-city-seo/
│   │   │   │   ├── index.blade.php                      (Optional)
│   │   │   │   ├── create.blade.php                     (Optional)
│   │   │   │   ├── edit.blade.php                       (Optional)
│   │   │   │   └── show.blade.php                       (Optional)
│   │   │   └── ...
│   │   ├── seo/
│   │   │   └── sitemap.blade.php                        🔄 UPDATED
│   │   └── layouts/
│   │       └── app.blade.php
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
│
├── routes/
│   ├── web.php                                          🔄 UPDATED
│   ├── api.php
│   └── console.php
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── ...
│
├── public/
│   ├── index.php
│   ├── uploads/
│   │   ├── services/
│   │   └── cities/
│   └── assets/
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   │   └── ServiceCitySeoTest.php (Optional)
│   └── Unit/
│       └── SchemaMarkupServiceTest.php (Optional)
│
├── IMPLEMENTATION_GUIDE.md                              ✨ NEW
├── SERVICE_CITY_SEO_QUICK_REF.md                        ✨ NEW
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── .env
├── .env.example
└── README.md
```

---

## 🏗️ Architecture Layers

### 1. **Route Layer** (`routes/`)
```
Frontend Routes:
  GET /{serviceSlug}-in-{citySlug}  → ServiceController@showServiceCity

Admin Routes:
  GET    /admin/service-city-seo           → index
  GET    /admin/service-city-seo/create    → create
  POST   /admin/service-city-seo           → store
  GET    /admin/service-city-seo/{id}      → show
  GET    /admin/service-city-seo/{id}/edit → edit
  PUT    /admin/service-city-seo/{id}      → update
  DELETE /admin/service-city-seo/{id}      → destroy
```

### 2. **Controller Layer** (`app/Http/Controllers/`)
```
Frontend:
  ServiceController
    ├── show(slug)           → Service detail
    └── showServiceCity()    → Service-city SEO page

Admin:
  ServiceCitySeoController
    ├── index()              → List all pages
    ├── create()             → Show form
    ├── store()              → Save new page
    ├── show(id)             → Display page
    ├── edit(id)             → Edit form
    ├── update(id)           → Update page
    ├── destroy(id)          → Delete page
    ├── toggleActive()       → Toggle status
    └── exportStats()        → JSON export
```

### 3. **Model Layer** (`app/Models/`)
```
Service
  ├── citySeoPages()        → HasMany relationship
  ├── activeCitySeoPages()  → Filtered relationship
  └── cities()              → BelongsToMany relationship

City
  ├── citySeoPages()        → HasMany relationship
  ├── activeCitySeoPages()  → Filtered relationship
  └── services()            → BelongsToMany relationship

ServiceCitySeo
  ├── service()             → BelongsTo
  ├── city()                → BelongsTo
  ├── Scopes: active()
  └── Accessors: slug, url, faq
```

### 4. **Service Layer** (`app/Services/`)
```
SchemaMarkupService (SEO)
  ├── breadcrumbSchema()
  ├── localBusinessSchema()
  ├── faqSchema()
  ├── organizationSchema()
  ├── aggregateRatingSchema()
  └── toJsonLd()

SitemapGeneratorService (SEO)
  ├── getServiceCitySitemapEntries()
  ├── getActiveServiceCityCombinations()
  ├── getPotentialCombinations()
  ├── getCoveragePercentage()
  ├── getMissingCombinations()
  ├── createMissingPages()
  ├── getStatistics()
  └── exportStats()
```

### 5. **View Layer** (`resources/views/`)
```
Frontend:
  service-city-seo.blade.php
    ├── Breadcrumb
    ├── Hero Section
    ├── Main Content (service description, custom content)
    ├── FAQ Section (accordion)
    ├── Related Services (same city)
    ├── Related Cities (same service)
    └── Schema.org Scripts

Admin:
  service-city-seo/
    ├── index.blade.php       (List with filters)
    ├── create.blade.php      (Create form)
    ├── edit.blade.php        (Edit form)
    └── show.blade.php        (Detail view)
```

### 6. **Command Layer** (`app/Console/Commands/`)
```
GenerateServiceCitySeoPages
  ├── --stats                 → Show statistics
  ├── --service=ID            → For specific service
  ├── --city=ID               → For specific city
  ├── --auto-approve          → Activate immediately
  └── Help text & examples
```

---

## 🔄 Data Flow

```
User visits: /pet-grooming-in-mohali
    ↓
Route matches: /{serviceSlug}-in-{citySlug}
    ↓
ServiceController@showServiceCity()
    ├── Find Service by slug
    ├── Find City by slug
    ├── Load ServiceCitySeo record
    ├── Get related services
    ├── Get related cities
    └── Render service-city-seo.blade.php
        ├── Include meta tags
        ├── Include schema scripts
        ├── Display content
        ├── Show FAQs
        └── Show related links

Result: SEO-optimized, cached HTML page
```

---

## 💾 Database Relationships

```sql
-- Entity-Relationship Diagram (ERD)

services (1) ──→ (M) service_city_seo ←─ (1) cities
                           │
                           ├── seo_title
                           ├── meta_description
                           ├── meta_keywords
                           ├── custom_content
                           ├── faq_content (JSON)
                           ├── is_active
                           ├── priority
                           ├── timestamps
                           └── soft_deletes

Key Constraints:
  - service_id → FOREIGN KEY → services(id) CASCADE DELETE
  - city_id → FOREIGN KEY → cities(id) CASCADE DELETE
  - UNIQUE(service_id, city_id)
```

---

## 📋 File Contents Summary

### Models
| File | Purpose | Lines |
|------|---------|-------|
| `ServiceCitySeo.php` | Main model with all methods | ~150 |
| `Service.php` | Updated with relationships | +50 |
| `City.php` | Updated with relationships | +50 |

### Controllers
| File | Purpose | Methods |
|------|---------|---------|
| `ServiceController.php` | Frontend display | `showServiceCity()` |
| `ServiceCitySeoController.php` | Admin CRUD + management | 9 methods |

### Services
| File | Purpose | Methods |
|------|---------|---------|
| `SchemaMarkupService.php` | Generate schema.org markup | 6 static methods |
| `SitemapGeneratorService.php` | Generate & manage sitemaps | 6 static methods |

### Migrations
| File | Purpose |
|------|---------|
| `*_create_service_city_seo_table.php` | Create main table |
| `*_add_slug_to_services_table.php` | Ensure slugs exist |
| `*_add_slug_to_cities_table.php` | Ensure slugs exist |

### Views
| File | Purpose | Sections |
|------|---------|----------|
| `service-city-seo.blade.php` | Main landing page | Hero, Content, FAQ, Sidebar, CTA |
| `sitemap.blade.php` | XML sitemap | Service-city entries added |

### Commands
| File | Purpose | Options |
|------|---------|---------|
| `GenerateServiceCitySeoPages.php` | CLI management | 5 options, help text |

---

## 🎯 Key Integration Points

### 1. Routes Integration
```php
// File: routes/web.php
Route::get('/{serviceSlug}-in-{citySlug}', 
    [FrontendServiceController::class, 'showServiceCity'])
    ->name('services.city');
```

### 2. Admin Menu Integration
Add to admin navigation:
```
📊 Management
  └─ SEO & Content
     ├─ Service-City SEO
     ├─ Sitemaps
     └─ Schema Manager
```

### 3. Settings Integration
Store global SEO settings:
```php
// Setting keys to add to 'settings' table
settings:
  - site_name
  - default_meta_description
  - phone
  - email
  - address
  - facebook_url
  - instagram_url
  - twitter_url
```

---

## 🚀 Deployment Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Run seeders: `php artisan db:seed --class=ServiceCitySeoSeeder`
- [ ] Generate pages: `php artisan seo:generate-pages`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Optimize: `php artisan optimize`
- [ ] Test URLs in browser
- [ ] Validate schemas: Google Rich Results Test
- [ ] Submit sitemap: Google Search Console
- [ ] Monitor: Google Analytics

---

## 📊 Statistics Dashboard

The admin panel shows:
```
┌─────────────────────────────────────┐
│ SEO Management Dashboard            │
├─────────────────────────────────────┤
│ Active Services        : 12         │
│ Active Cities          : 5          │
│ Active Combinations    : 45         │
│ Potential Combos       : 60         │
│ Coverage               : 75%        │
│ Missing                : 15         │
└─────────────────────────────────────┘
```

---

## 🔍 Code Examples

### Add Service-City SEO in View
```blade
@if($page)
    <h1>{{ $page->seo_title }}</h1>
    <p>{{ $page->meta_description }}</p>
    {!! $page->custom_content !!}
@endif
```

### Query in Controller
```php
$page = ServiceCitySeo::with(['service', 'city'])
    ->where('is_active', true)
    ->where('service_id', $serviceId)
    ->where('city_id', $cityId)
    ->firstOrFail();
```

### Generate URL
```php
route('services.city', [
    'serviceSlug' => 'pet-grooming',
    'citySlug' => 'mohali'
])
// Output: /pet-grooming-in-mohali
```

---

## 📚 Documentation Files

1. **IMPLEMENTATION_GUIDE.md** - Comprehensive setup & usage
2. **SERVICE_CITY_SEO_QUICK_REF.md** - Quick reference
3. **FOLDER_STRUCTURE.md** - This file
4. **Inline Code Comments** - Self-documenting code

---

## 🔐 Security Considerations

1. **Validation**: All inputs validated in controller
2. **Authorization**: Use middleware for admin routes
3. **SQL Injection**: Using Eloquent with parameterized queries
4. **XSS**: Blade escapes output by default, use `{!!` only for trusted content
5. **Mass Assignment**: Fillable arrays defined in models

---

## ⚡ Performance Optimization

1. **Eager Loading**: Controller uses `with(['service', 'city'])`
2. **Pagination**: Admin lists paginate 20 items
3. **Indexes**: Database indexes on frequently queried columns
4. **Query Optimization**: Service-city lookup uses indexes
5. **Caching**: Consider caching schema generation

---

## 🧪 Testing Strategy

```
tests/
├── Feature/
│   ├── ServiceCitySeoTest.php
│   │   ├── testPageLoads()
│   │   ├── testPageNotFound()
│   │   ├── testInactivePageReturns404()
│   │   └── testSEOMetaTagsPresent()
│   └── SitemapTest.php
└── Unit/
    ├── SchemaMarkupServiceTest.php
    ├── SitemapGeneratorServiceTest.php
    └── ServiceCitySeoModelTest.php
```

---

**Last Updated:** 2024-06-01  
**Laravel Version:** 11.x  
**PHP Version:** 8.2+
