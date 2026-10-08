<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme_primary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'theme_primary_dark_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'theme_secondary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'theme_accent_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'theme_button_text_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'admin_theme_primary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'admin_theme_primary_dark_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);

        $fields = [
            'site_name',
            'site_tagline',
            'site_email',
            'site_phone',
            'site_address',
            'default_meta_title',
            'default_meta_description',
            'default_meta_keywords',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
            'whatsapp_number',
            // Payment (Razorpay shared keys)
            'razorpay_key_id',
            'razorpay_key_secret',
            // MSG91 WhatsApp
            'msg91_authkey',
            'msg91_integrated_number',
            // Theme
            'theme_primary_color',
            'theme_primary_dark_color',
            'theme_secondary_color',
            'theme_accent_color',
            'theme_button_text_color',
            'admin_theme_primary_color',
            'admin_theme_primary_dark_color',
            // Vaccination booking fee amount
            'booking_fee_amount',
            // Appointment booking fee amount
            'appt_fee_amount',
        ];

        // Per-module checkboxes
        Setting::set('adoption_payment_enabled', $request->has('adoption_payment_enabled') ? '1' : '0');
        Setting::set('adoption_otp_enabled',     $request->has('adoption_otp_enabled')     ? '1' : '0');
        Setting::set('booking_payment_enabled',  $request->has('booking_payment_enabled')  ? '1' : '0');
        Setting::set('booking_otp_enabled',      $request->has('booking_otp_enabled')      ? '1' : '0');
        Setting::set('appt_payment_enabled',     $request->has('appt_payment_enabled')     ? '1' : '0');
        Setting::set('appt_otp_enabled',         $request->has('appt_otp_enabled')         ? '1' : '0');
        Setting::set('msg91_enabled',            $request->has('msg91_enabled')            ? '1' : '0');
        // Keep legacy key in sync for any code that may still reference it
        Setting::set('payment_enabled', $request->has('adoption_payment_enabled') ? '1' : '0');

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $filename = 'logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            Setting::set('site_logo', $filename);
        }

        if ($request->hasFile('site_favicon')) {
            $file = $request->file('site_favicon');
            $filename = 'favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            Setting::set('site_favicon', $filename);
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function testRazorpay(Request $request)
    {
        $keyId = Setting::get('razorpay_key_id', '');
        $keySecret = Setting::get('razorpay_key_secret', '');

        if (!$keyId || !$keySecret) {
            return response()->json([
                'success' => false,
                'message' => 'Razorpay API keys are not configured.',
            ]);
        }

        try {
            // Test API connection with a minimal request
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($keyId, $keySecret)
                ->get('https://api.razorpay.com/v1/items');

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Razorpay API connection successful!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'API connection failed: ' . $response->status() . ' - ' . $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ]);
        }
    }

    public function integrations()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.integrations', compact('settings'));
    }

    public function updateIntegrations(Request $request)
    {
        // 1. Validation
        $request->validate([
            'tinymce_api_key'      => 'nullable|string|max:255',
            'tiny_drive_api_key'   => 'nullable|string|max:255',
            'extra_api_settings'   => 'nullable|string',
            'recaptcha_site_key'   => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
            'razorpay_key_id'      => 'nullable|string|max:255',
            'razorpay_key_secret'  => 'nullable|string|max:255',
        ]);

        // 2. Define fields to update
        $fields = [
            'tinymce_api_key',
            'tiny_drive_api_key',
            'extra_api_settings',
            'recaptcha_site_key',
            'recaptcha_secret_key',
            'razorpay_key_id',
            'razorpay_key_secret',
        ];

        // 3. Save Text Fields
        foreach ($fields as $field) {
            // Sirf wahi update karein jo request mein hain ya empty string set karein agar user ne clear kiya ho
            Setting::set($field, (string) $request->input($field, ''));
        }

        // 4. Save Checkboxes (Inka value request mein tabhi aata hai jab ticked hon)
        Setting::set('recaptcha_enabled', $request->has('recaptcha_enabled') ? '1' : '0');

        return back()->with('success', 'API & Integrations settings updated successfully!');
    }

    // ──────────────────────────────────────────────
    // Admin Account (email, password, SMTP)
    // ──────────────────────────────────────────────
    public function account()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.account', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Current password is incorrect.'])->withInput();
        }

        $user->email = $request->email;
        $user->save();

        return back()->with('email_success', 'Login email updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('password_success', 'Password changed successfully.');
    }

    public function updateSmtp(Request $request)
    {
        $request->validate([
            'mail_host'         => 'nullable|string|max:255',
            'mail_port'         => 'nullable|integer',
            'mail_username'     => 'nullable|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'mail_encryption'   => 'nullable|in:tls,ssl,starttls',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name'    => 'nullable|string|max:255',
        ]);

        $smtpValues = [
            'mail_host' => (string) $request->input('mail_host', ''),
            'mail_port' => (string) $request->input('mail_port', ''),
            'mail_username' => (string) $request->input('mail_username', ''),
            'mail_encryption' => (string) $request->input('mail_encryption', ''),
            'mail_from_address' => (string) $request->input('mail_from_address', ''),
            'mail_from_name' => (string) $request->input('mail_from_name', ''),
        ];

        foreach ($smtpValues as $field => $value) {
            Setting::set($field, $value);
        }

        if ($request->filled('mail_password')) {
            Setting::set('mail_password', $request->input('mail_password'));
        }

        $smtpValues['mail_password'] = Setting::get('mail_password', '');

        $this->syncMailEnv($smtpValues);

        // Apply changes immediately for the current request lifecycle.
        config([
            'mail.default' => $smtpValues['mail_host'] !== '' ? 'smtp' : config('mail.default'),
            'mail.mailers.smtp.host' => $smtpValues['mail_host'] !== '' ? $smtpValues['mail_host'] : config('mail.mailers.smtp.host'),
            'mail.mailers.smtp.port' => $smtpValues['mail_port'] !== '' ? (int) $smtpValues['mail_port'] : config('mail.mailers.smtp.port'),
            'mail.mailers.smtp.username' => $smtpValues['mail_username'],
            'mail.mailers.smtp.password' => $smtpValues['mail_password'],
            'mail.mailers.smtp.encryption' => $smtpValues['mail_encryption'] !== '' ? $smtpValues['mail_encryption'] : null,
            'mail.from.address' => $smtpValues['mail_from_address'] !== '' ? $smtpValues['mail_from_address'] : config('mail.from.address'),
            'mail.from.name' => $smtpValues['mail_from_name'] !== '' ? $smtpValues['mail_from_name'] : config('mail.from.name'),
        ]);

        return back()->with('smtp_success', 'SMTP settings saved in DB and .env. Send a test email to verify.');
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'notification_email' => [
                'nullable',
                'string',
                'max:1000',
                function ($attribute, $value, $fail) {
                    if (empty($value)) return;
                    $emails = array_map('trim', explode(',', $value));
                    foreach ($emails as $email) {
                        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $fail("\"$email\" is not a valid email address.");
                        }
                    }
                },
            ],
        ]);

        Setting::set('notification_email', trim($request->input('notification_email', '')));

        return back()->with('notif_success', 'Notification email(s) saved successfully.');
    }

    public function testMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Mail::raw('This is a test email from PawCare SMTP configuration.', function ($message) use ($request) {
                $message->to($request->test_email)
                    ->subject('SMTP Test Email - PawCare');
            });

            return back()->with('test_mail_success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('test_mail_error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    private function syncMailEnv(array $smtpValues): void
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath) || !File::isWritable($envPath)) {
            return;
        }

        $content = File::get($envPath);
        $replacements = [
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => $smtpValues['mail_host'] ?? '',
            'MAIL_PORT' => $smtpValues['mail_port'] ?? '',
            'MAIL_USERNAME' => $smtpValues['mail_username'] ?? '',
            'MAIL_PASSWORD' => $smtpValues['mail_password'] ?? '',
            'MAIL_ENCRYPTION' => $smtpValues['mail_encryption'] ?? '',
            'MAIL_FROM_ADDRESS' => $smtpValues['mail_from_address'] ?? '',
            'MAIL_FROM_NAME' => $smtpValues['mail_from_name'] ?? '',
        ];

        foreach ($replacements as $key => $value) {
            $line = $key . '=' . $this->formatEnvValue((string) $value);
            $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';

            if (preg_match($pattern, $content)) {
                $content = (string) preg_replace($pattern, $line, $content);
            } else {
                $content .= PHP_EOL . $line;
            }
        }

        File::put($envPath, $content);
    }

    private function formatEnvValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (preg_match('/^[A-Za-z0-9_\-\.\/@:]+$/', $value)) {
            return $value;
        }

        return '"' . str_replace('"', '\\"', $value) . '"';
    }
}
