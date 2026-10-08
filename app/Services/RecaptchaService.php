<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public static function isEnabled(): bool
    {
        return Setting::get('recaptcha_enabled', '0') === '1';
    }

    public static function getSiteKey(): string
    {
        return Setting::get('recaptcha_site_key', '');
    }

    /**
     * Verify a reCAPTCHA v2 token.
     * Returns true if disabled (skip check), true if valid, false if invalid.
     */
    public static function verify(?string $token): bool
    {
        if (!static::isEnabled()) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        $secret = Setting::get('recaptcha_secret_key', '');
        if (empty($secret)) {
            return true; // not configured — skip
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $secret,
            'response' => $token,
        ]);

        return (bool) ($response->json('success') ?? false);
    }
}
