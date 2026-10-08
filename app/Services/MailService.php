<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Send a mailable to the configured notification email address(es).
     * Reads from Admin → My Account → Form Notification Emails (notification_email setting).
     * Falls back to mail_from_address or site_email if not set.
     */
    public static function sendToAdmin(Mailable $mailable): bool
    {
        // Resolve recipient list
        $raw = Setting::get('notification_email')
            ?: Setting::get('mail_from_address')
            ?: Setting::get('site_email');

        if (!$raw) {
            Log::warning('MailService: No notification email configured. Set it in Admin → My Account → Form Notification Emails.');
            return false;
        }

        // Support multiple comma-separated emails
        $recipients = array_filter(
            array_map('trim', explode(',', $raw)),
            fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL)
        );

        if (empty($recipients)) {
            Log::warning('MailService: notification_email contains no valid email addresses.', ['raw' => $raw]);
            return false;
        }

        try {
            self::configureMailer();
            Mail::to($recipients)->send($mailable);
            return true;
        } catch (\Throwable $e) {
            Log::error('MailService: Failed to send notification email.', [
                'to'    => implode(', ', $recipients),
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Dynamically configure the mailer from DB settings so admin SMTP
     * settings (saved via Admin > My Account) are used at runtime.
     */
    private static function configureMailer(): void
    {
        $host       = Setting::get('mail_host');
        $port       = Setting::get('mail_port');
        $username   = Setting::get('mail_username');
        $password   = Setting::get('mail_password');
        $encryption = Setting::get('mail_encryption');
        $fromAddr   = Setting::get('mail_from_address');
        $fromName   = Setting::get('mail_from_name', Setting::get('site_name', 'PawZone'));

        if (!$host || !$username) {
            // Fall back to .env / log driver — no DB config yet
            return;
        }

        config([
            'mail.mailers.smtp.host'       => $host,
            'mail.mailers.smtp.port'       => (int) ($port ?: 587),
            'mail.mailers.smtp.username'   => $username,
            'mail.mailers.smtp.password'   => $password,
            'mail.mailers.smtp.encryption' => $encryption ?: 'tls',
            'mail.mailers.smtp.timeout'    => 30,
            'mail.default'                 => 'smtp',
            'mail.from.address'            => $fromAddr,
            'mail.from.name'               => $fromName,
        ]);
    }
}
