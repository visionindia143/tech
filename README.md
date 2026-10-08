# 🐾 PawCare - Pet Shop & Pet Care Platform

A fully dynamic, modern, scalable, and SEO-friendly Pet Shop & Pet Care platform built with **Laravel 11**, featuring a premium frontend, complete admin panel, blog system, and vaccination booking.

---

## ✨ Features

### Frontend
- **Hero Slider** — Dynamic, auto-rotating slider managed from admin
- **Pet Gallery** — Showcase pets with hover effects and breed info
- **Pet Care Services** — Card-based layout with icons, descriptions, and pricing
- **Available Cities** — Visual city cards showing service coverage
- **Blog System** — Full blog with listing, detail, search, pagination, related posts
- **Vaccination Booking** — Complete booking form with validation
- **About & Contact Pages** — Dynamic content managed from admin
- **Premium UI** — Modern design with Outfit + Playfair Display fonts, smooth animations

### Admin Panel (`/admin`)
- **Dashboard** — Stats overview with recent bookings
- **Blog Management** — Full CRUD with rich text editor, SEO fields, featured images
- **Slider Management** — Add/edit/delete hero sliders
- **Gallery Management** — Pet gallery with type, breed, ordering
- **Services Management** — Services with icons, pricing, descriptions
- **Cities Management** — Service coverage cities
- **Vaccination Types** — Manage available vaccination options
- **Vaccination Bookings** — View, filter, update status (pending/confirmed/completed/cancelled)
- **Pages Management** — Dynamic pages with SEO fields
- **Settings** — Site name, logo, favicon, contact info, social links, SEO defaults

### SEO Optimization
- Dynamic meta tags (title, description, keywords) per page/blog
- Open Graph tags for social sharing
- Twitter Card meta tags
- Canonical URLs
- `sitemap.xml` with all blog and page URLs
- `robots.txt`
- `llm.txt` for AI SEO
- Clean slug URLs (`/blog/post-name`)
- JSON-LD structured data on blog posts
- Semantic HTML structure

---

## 🛠 Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Blade templates, vanilla CSS & JS
- **Fonts:** Google Fonts (Outfit, Playfair Display)
- **Icons:** Font Awesome 6
- **Rich Text:** TinyMCE (CDN)
- **Database:** MySQL / MariaDB / SQLite

---

## 🚀 Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+ (or SQLite)
- Node.js (optional, for asset compilation)

### Setup Steps

```bash
# 1. Clone or copy the project
cd petshop

# 2. Install dependencies
composer install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
# DB_CONNECTION=mysql
# DB_DATABASE=petshop
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Run migrations
php artisan migrate

# 6. Seed sample data
php artisan db:seed

# 7. Create storage symlink
php artisan storage:link

# 8. Create upload directories
mkdir -p public/uploads/{blogs,sliders,galleries,services,cities,pages,settings}

# 9. Start the server
php artisan serve
```

### Access
- **Frontend:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin

---

## 📁 Project Structure

```
petshop/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # All admin controllers
│   │   │   ├── BlogController.php
│   │   │   ├── BookingController.php
│   │   │   ├── CityController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── GalleryController.php
│   │   │   ├── PageController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── SettingController.php
│   │   │   ├── SliderController.php
│   │   │   └── VaccinationTypeController.php
│   │   └── Frontend/
│   │       └── HomeController.php
│   └── Models/             # All Eloquent models
├── database/
│   ├── migrations/         # 10 migration files
│   └── seeders/            # Sample data seeder
├── resources/views/
│   ├── layouts/
│   │   ├── frontend.blade.php   # Main frontend layout
│   │   └── admin.blade.php      # Admin panel layout
│   ├── frontend/           # All frontend pages
│   └── admin/              # All admin CRUD views
├── routes/
│   └── web.php             # All routes
└── public/uploads/         # File uploads directory
```

---

## 📊 Database Tables

| Table | Purpose |
|-------|---------|
| `settings` | Key-value site settings |
| `blogs` | Blog posts with SEO fields |
| `sliders` | Hero slider items |
| `galleries` | Pet gallery images |
| `services` | Pet care services |
| `cities` | Available service cities |
| `vaccination_types` | Types of vaccinations |
| `vaccination_bookings` | Booking submissions |
| `pages` | Dynamic pages (About, etc.) |
| `contacts` | Contact form submissions |

---

## 🔐 Adding Authentication

For production, add Laravel's built-in auth:

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
php artisan migrate
```

Then add `middleware(['auth'])` to admin routes in `routes/web.php`.

---

## 📝 License

This project is open-source and available under the [MIT License](LICENSE).
