# Service-City SEO Landing Pages - Implementation Guide

## 📋 Table of Contents
1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Database Structure](#database-structure)
4. [Setup Instructions](#setup-instructions)
5. [Usage Guide](#usage-guide)
6. [URL Structure](#url-structure)
7. [SEO Features](#seo-features)
8. [Admin Panel](#admin-panel)
9. [CLI Commands](#cli-commands)
10. [Examples](#examples)
11. [Troubleshooting](#troubleshooting)

---

## Overview

This system creates **SEO-friendly, dynamic city-specific landing pages** for your services without duplicating code or maintaining separate controllers/views.

### Key Features
✅ Single controller method for all service-city pages  
✅ Single Blade template reused for all combinations  
✅ Dynamic URL generation: `/pet-grooming-in-mohali`  
✅ Admin panel for managing content  
✅ Schema.org markup (Breadcrumb, LocalBusiness, FAQ, AggregateRating)  
✅ Automatic sitemap generation  
✅ Support for custom content per service-city combo  
✅ FAQ management per combination  
✅ Bulk generation of missing combinations  

---

## Architecture

### Files Created/Modified

```
app/
├── Models/
│   ├── Service.php (UPDATED - added relationships)
│   ├── City.php (UPDATED - added relationships)
│   └── ServiceCitySeo.php (NEW)
├── Http/Controllers/
│   ├── Frontend/
│   │   └── ServiceController.php (UPDATED - added showServiceCity method)
│   └── Admin/
│       └── ServiceCitySeoController.php (NEW)
├── Services/
│   ├── SchemaMarkupService.php (NEW)
│   └── SitemapGeneratorService.php (NEW)
├── Console/Commands/
│   └── GenerateServiceCitySeoPages.php (NEW)
├── Models/
│   └── ServiceCitySeo.php (NEW)
database/
├── migrations/
│   ├── 2024_01_01_000050_create_service_city_seo_table.php (NEW)
│   ├── 2024_01_01_000051_add_slug_to_services_table.php (NEW)
│   └── 2024_01_01_000052_add_slug_to_cities_table.php (NEW)
└── seeders/
    └── ServiceCitySeoSeeder.php (NEW)
resources/views/
├── frontend/
│   └── service-city-seo.blade.php (NEW)
└── seo/
    └── sitemap.blade.php (UPDATED)
routes/
└── web.php (UPDATED - added routes)
```

---

## Database Structure

### service_city_seo Table

```sql
CREATE TABLE service_city_seo (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    service_id BIGINT NOT NULL FOREIGN KEY,
    city_id BIGINT NOT NULL FOREIGN KEY,
    seo_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    custom_content LONGTEXT,
    faq_content JSON,
    is_active BOOLEAN DEFAULT TRUE,
    priority INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP,
    
    UNIQUE KEY unique_service_city (service_id, city_id),
    INDEX idx_active_service (is_active, service_id),
    INDEX idx_active_city (is_active, city_id),
    INDEX idx_priority (priority)
);
```

### Model Relationships

```
Service (1) ──→ (Many) ServiceCitySeo ──→ (1) City
Service (Many) ──→ (Many) City [through ServiceCitySeo]
```

---

## Setup Instructions

### Step 1: Run Migrations

```bash
php artisan migrate
```

This will:
- Create `service_city_seo` table
- Add `slug` column to `services` table (if missing)
- Add `slug` column to `cities` table (if missing)

### Step 2: Seed Sample Data (Optional)

```bash
php artisan db:seed --class=ServiceCitySeoSeeder
```

### Step 3: Run CLI Command to Generate Missing Pages

```bash
# Show statistics
php artisan seo:generate-pages --stats

# Generate all missing combinations
php artisan seo:generate-pages

# Generate with auto-approval
php artisan seo:generate-pages --auto-approve

# Generate for specific service
php artisan seo:generate-pages --service=1

# Generate for specific city
php artisan seo:generate-pages --city=1

# Generate single combination
php artisan seo:generate-pages --service=1 --city=1
```

### Step 4: Verify Installation

```bash
# Check routes
php artisan route:list | grep services.city

# Test URL
# Visit: http://localhost/pet-grooming-in-mohali
```

---

## Usage Guide

### Creating SEO Pages from Admin Panel

1. Navigate to: **Admin → Service-City SEO**
2. Click **"Create New Page"**
3. Fill in:
   - Service (required)
   - City (required)
   - SEO Title (auto-generated if empty)
   - Meta Description
   - Meta Keywords
   - Custom Content (HTML/Markdown allowed)
   - FAQ Items
   - Active Status
   - Priority (higher = appears first in related links)
4. Click **Save**

### Bulk Generate Missing Pages

1. Navigate to: **Admin → Service-City SEO**
2. Click **"Bulk Generate Missing Pages"**
3. Optionally check **"Auto-approve"** to activate all generated pages
4. Click **Generate**

### Edit Existing Page

1. Navigate to: **Admin → Service-City SEO**
2. Find the page in the list
3. Click **Edit**
4. Update content
5. Click **Save**

### Delete Page

1. Navigate to: **Admin → Service-City SEO**
2. Click **Delete** button
3. Confirm deletion

---

## URL Structure

### Generated URLs

```
/pet-grooming-in-mohali
/pet-grooming-in-chandigarh
/pet-grooming-in-panchkula
/pet-boarding-in-mohali
/pet-vaccination-in-chandigarh
/pet-training-in-panchkula
```

### URL Format
```
/{service-slug}-in-{city-slug}
```

### Dynamic Route
```php
Route::get('/{serviceSlug}-in-{citySlug}', [FrontendServiceController::class, 'showServiceCity'])
    ->name('services.city');
```

---

## SEO Features

### 1. Meta Tags

Automatically generated in Blade template:
```html
<title>{{ $page->seo_title }}</title>
<meta name="description" content="{{ $page->meta_description }}">
<meta name="keywords" content="{{ $page->meta_keywords }}">
<link rel="canonical" href="{{ $page->url }}" />
```

### 2. Schema.org Markup

Automatically included in page:

#### Breadcrumb Schema
```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Home"},
    {"@type": "ListItem", "position": 2, "name": "Pet Grooming"},
    {"@type": "ListItem", "position": 3, "name": "Mohali"},
    {"@type": "ListItem", "position": 4, "name": "Pet Grooming in Mohali"}
  ]
}
```

#### LocalBusiness Schema
```json
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "PawZone - Pet Grooming",
  "description": "Professional pet grooming in Mohali",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Mohali"
  }
}
```

#### FAQ Schema
Auto-generated from FAQ content in the database.

#### Organization Schema
Includes company info from settings.

### 3. Sitemap Generation

Automatically included in `/sitemap.xml`:
```xml
<url>
    <loc>https://example.com/pet-grooming-in-mohali</loc>
    <lastmod>2024-01-01T12:00:00Z</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
</url>
```

---

## Admin Panel

### Dashboard View

Shows statistics:
- Active Services
- Active Cities
- Active Service-City Combinations
- Potential Combinations
- Coverage Percentage
- Missing Combinations

### List View Features

- **Search** by service or city name
- **Filter** by service, city, or status
- **Sort** by priority or date
- **Pagination** (20 items per page)
- **Bulk Actions** (generate missing, approve, delete)

### Edit Form Fields

```
□ Service (select, required)
□ City (select, required)
□ SEO Title (text, max 60 chars, optional)
□ Meta Description (textarea, max 160 chars)
□ Meta Keywords (textarea, max 255 chars)
□ Custom Content (rich text editor)
□ FAQ Items (JSON editor or repeater)
☑ Active (checkbox)
⊙ Priority (number, 0-100)
```

---

## CLI Commands

### Generate Pages

```bash
# Show help
php artisan seo:generate-pages --help

# Statistics only
php artisan seo:generate-pages --stats

# Generate all missing combinations
php artisan seo:generate-pages

# Auto-approve generated pages
php artisan seo:generate-pages --auto-approve

# Generate for specific service (ID=1)
php artisan seo:generate-pages --service=1

# Generate for specific city (ID=1)
php artisan seo:generate-pages --city=1

# Single combination
php artisan seo:generate-pages --service=1 --city=1
```

### Output Example
```
🚀 Starting SEO page generation...
═══════════════════════════════
✅ Created: 45
❌ Failed: 2
📊 Total: 47
═══════════════════════════════
```

---

## Examples

### Example 1: Create Pet Grooming in Mohali

**Admin Form Values:**
```
Service: Pet Grooming
City: Mohali
SEO Title: Professional Pet Grooming in Mohali - PawZone
Meta Description: Get professional pet grooming in Mohali. Expert groomers, hygienic environment, 100% satisfaction guaranteed!
Meta Keywords: pet grooming, dog grooming Mohali, cat grooming, pet spa
Priority: 10
Active: Yes
```

**Generated:**
```
URL: /pet-grooming-in-mohali
Title Tag: Professional Pet Grooming in Mohali - PawZone
Meta Description: Get professional pet grooming...
Sitemap Entry: Added automatically
Schema: Breadcrumb, LocalBusiness, Organization
```

### Example 2: FAQ Content in JSON

```json
[
  {
    "question": "How long does grooming take?",
    "answer": "It typically takes 1-3 hours depending on your pet's size and coat condition."
  },
  {
    "question": "Is grooming safe for senior pets?",
    "answer": "Yes, we have gentle techniques suitable for senior pets. Consult our experts."
  },
  {
    "question": "How often should I get my pet groomed?",
    "answer": "Monthly grooming is recommended for most breeds. Consult our team for specific needs."
  }
]
```

### Example 3: Custom Content with HTML

```html
<h2>Why Choose PawZone for Pet Grooming in Mohali?</h2>

<div class="benefits">
  <p>We provide comprehensive pet grooming services with trained professionals:</p>
  <ul>
    <li>✓ Professional groomers with 5+ years experience</li>
    <li>✓ Hygienic facilities and sterilized tools</li>
    <li>✓ Gentle handling for all breeds</li>
    <li>✓ Same-day services available</li>
    <li>✓ Reasonable pricing</li>
  </ul>
</div>

<h3>Our Grooming Services Include:</h3>
<ol>
  <li>Full body wash and dry</li>
  <li>Nail trimming and paw care</li>
  <li>Ear and eye cleaning</li>
  <li>De-shedding treatment</li>
  <li>Breed-specific cuts</li>
</ol>
```

---

## Troubleshooting

### Issue: Route not found for service-city URL

**Solution:**
1. Ensure migrations have run: `php artisan migrate`
2. Clear route cache: `php artisan route:clear`
3. Check URL format matches: `/{serviceSlug}-in-{citySlug}`
4. Verify service and city slugs don't have uppercase

### Issue: SEO page returns 404

**Reasons:**
- Service is not active (`is_active = false`)
- City is not active (`is_active = false`)
- SEO page is not active (`is_active = false`)
- Service-city combination doesn't exist in database

**Solution:**
```bash
# Create the combination
php artisan seo:generate-pages --service=ID --city=ID --auto-approve

# Or use admin panel to create manually
```

### Issue: Schemas not showing in Google Rich Results

**Check:**
1. View page source - look for `<script type="application/ld+json">`
2. Validate in Google Rich Results Test: https://search.google.com/test/rich-results
3. Wait 24-48 hours for Google to re-crawl
4. Ensure all required fields are filled in admin form

### Issue: Duplicate content warning

**Solution:**
- Ensure each service-city combination has unique `seo_title` and `meta_description`
- Add custom_content specific to the city/service combo
- Sitemap deduplication happens automatically

### Issue: Performance slow with many combinations

**Optimization:**
```php
// Use pagination in admin
$seoPages = ServiceCitySeo::paginate(20);

// Use eager loading in controller
$page = ServiceCitySeo::with(['service', 'city'])->find($id);

// Add indexes (already done in migration)
```

---

## Best Practices

### 1. SEO Content

✅ **DO:**
- Create unique titles for each city
- Add city-specific content
- Include local keywords
- Use 150-160 char descriptions
- Add FAQ for common questions

❌ **DON'T:**
- Copy-paste content across all cities
- Use generic descriptions
- Ignore local keywords
- Exceed character limits

### 2. URL Structure

✅ **DO:**
- Use lowercase slugs
- Use hyphens for spaces
- Keep it short and descriptive
- Make it readable

❌ **DON'T:**
- Use underscores or spaces
- Use special characters
- Make slugs too long
- Use abbreviations

### 3. Priority Field

```
Priority 0-100: Higher = more prominent in related lists
- 90-100: High-priority cities
- 50-80: Medium-priority cities
- 0-50: Low-priority cities
```

### 4. Bulk Generation

- Generate all missing pages
- Review and approve before activating
- Edit each page to add custom content
- Never leave auto-generated content as-is

---

## API Examples

### Get service-city page by slug

```php
$page = ServiceCitySeo::with(['service', 'city'])
    ->whereHas('service', function ($q) {
        $q->where('slug', 'pet-grooming');
    })
    ->whereHas('city', function ($q) {
        $q->where('slug', 'mohali');
    })
    ->where('is_active', true)
    ->first();
```

### Get all pages for a service

```php
$pages = $service->activeCitySeoPages()
    ->with('city')
    ->orderBy('priority', 'desc')
    ->get();
```

### Get all pages for a city

```php
$pages = $city->activeCitySeoPages()
    ->with('service')
    ->orderBy('priority', 'desc')
    ->get();
```

### Get statistics

```php
use App\Services\SitemapGeneratorService;

$stats = SitemapGeneratorService::getStatistics();
// Returns: active_services, active_cities, active_combinations, etc.
```

---

## Support & Next Steps

1. **Review** the generated pages in admin panel
2. **Edit** each page with unique, localized content
3. **Test** URLs in browser to ensure they load
4. **Validate** schema markup with Google Rich Results Test
5. **Monitor** in Google Search Console for impressions
6. **Update** content regularly to maintain freshness

---

Generated: 2024-01-01  
Laravel Version: 11  
Last Updated: 2024-06-01
