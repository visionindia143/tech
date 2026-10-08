<?php

namespace App\Providers;

use App\Models\AdoptionBooking;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\TestBooking;
use App\Models\VaccinationBooking;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Dynamic SMTP config from DB (overrides .env if set)
        try {
            $host = Setting::get('mail_host');
            if ($host) {
                Config::set('mail.mailers.smtp.host',       $host);
                Config::set('mail.mailers.smtp.port',       (int) Setting::get('mail_port', 587));
                Config::set('mail.mailers.smtp.username',   Setting::get('mail_username', ''));
                Config::set('mail.mailers.smtp.password',   Setting::get('mail_password', ''));
                Config::set('mail.mailers.smtp.encryption', Setting::get('mail_encryption', 'tls') ?: null);
                Config::set('mail.from.address',            Setting::get('mail_from_address', config('mail.from.address')));
                Config::set('mail.from.name',               Setting::get('mail_from_name', config('mail.from.name')));
                Config::set('mail.default', 'smtp');
            }
        } catch (\Exception $e) {
            // DB not ready (migrations, artisan commands) — skip silently
        }

        // Override the password reset URL so it points to our admin route
        // instead of Laravel's default `password.reset` (which doesn't exist here).
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return url(route('admin.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        });

        // Frontend settings — shared with layout AND all frontend child views
        // Static cache so DB is hit only once per request across multiple composers
        View::composer(['layouts.frontend', 'frontend.*'], function ($view) {
            static $cache = null;
            if ($cache === null) {
                $cache = [
                'site_name'               => Setting::get('site_name', 'PawCare'),
                'site_tagline'            => Setting::get('site_tagline', 'Your trusted partner for premium pet care, vaccinations, grooming and everything your furry friend needs.'),
                'site_logo'               => Setting::get('site_logo'),
                'site_favicon'            => Setting::get('site_favicon'),
                'site_email'              => Setting::get('site_email'),
                'site_phone'              => Setting::get('site_phone'),
                'site_address'            => Setting::get('site_address'),
                'whatsapp_number'         => Setting::get('whatsapp_number'),
                'default_meta_title'      => Setting::get('default_meta_title', 'PawCare - Premium Pet Shop & Pet Care'),
                'default_meta_description'=> Setting::get('default_meta_description', 'Your trusted partner for pet care, vaccinations, grooming and more.'),
                'default_meta_keywords'   => Setting::get('default_meta_keywords', 'pet care, pet shop, vaccination, grooming'),
                'facebook_url'            => Setting::get('facebook_url'),
                'instagram_url'           => Setting::get('instagram_url'),
                'twitter_url'             => Setting::get('twitter_url'),
                'youtube_url'             => Setting::get('youtube_url'),
                'theme_primary'           => Setting::get('theme_primary_color', '#FF6B35'),
                'theme_primary_dark'      => Setting::get('theme_primary_dark_color', '#E85520'),
                'theme_secondary'         => Setting::get('theme_secondary_color', '#2D3142'),
                'theme_accent'            => Setting::get('theme_accent_color', '#06D6A0'),
                'theme_button_text'       => Setting::get('theme_button_text_color', '#FFFFFF'),
                ];
            }
            $view->with('settings', $cache);
        });

        // Admin layout + content views settings
        View::composer(['layouts.admin', 'admin.*'], function ($view) {
            static $cache = null;
            if ($cache === null) {
                try {
                    $unreadContacts  = Contact::where('is_read', false)->count();
                    $pendingBookings = VaccinationBooking::where('status', 'pending')->count()
                                     + TestBooking::where('status', 'pending')->count()
                                     + AdoptionBooking::where('status', 'pending')->count();
                } catch (\Exception $e) {
                    $unreadContacts = $pendingBookings = 0;
                }
                $cache = [
                    'site_name'        => Setting::get('site_name', 'PawCare'),
                    'site_logo'        => Setting::get('site_logo'),
                    'tinymce_api_key'  => Setting::get('tinymce_api_key', 'no-api-key') ?: 'no-api-key',
                    'theme_primary'    => Setting::get('admin_theme_primary_color', '#FF6B35'),
                    'theme_primary_dark' => Setting::get('admin_theme_primary_dark_color', '#E85520'),
                    'unread_contacts'  => $unreadContacts,
                    'pending_bookings' => $pendingBookings,
                ];
            }
            $view->with('adminSettings', $cache);
        });
    }
}
