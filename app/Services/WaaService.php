<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class WaaService
{

    private string $apiUrl;
    private string $apiKey;
    private string $session;

    public function __construct()
    {
        $this->apiUrl  = Setting::get('wa_api_url', '');
        $this->apiKey  = Setting::get('wa_api_key', '');
        $this->session = Setting::get('wa_session_key', '');

        Log::info('WAA CONFIG', [
            'url' => $this->apiUrl,
            'key' => $this->apiKey,
            'session' => $this->session,
        ]);
    }
    /**
     * Send OTP
     */
    public function sendOtp(string $phone, string $customerName, string $otp, string $context = 'booking'): bool
    {
        $message = "Hello {$customerName}, Your OTP for {$context} is *{$otp}*";

        return $this->sendMessage($phone, $message, $customerName);
    }

    /**
     * Booking Notification
     */
    public function sendBookingNotification(string $phone, string $customerName, string $message): bool
    {
        $finalMessage = "Hello {$customerName},\n{$message}";

        return $this->sendMessage($phone, $finalMessage, $customerName);
    }

    public function isEnabled(): bool
    {
        return Setting::get('wa_enabled', '0') === '1'
            && !empty($this->apiUrl)
            && !empty($this->apiKey)
            && !empty($this->session);
    }



    /**
     * Booking Confirmed
     */
    public function sendBookingConfirmed(string $phone, string $customerName, string $petName, int $bookingId): bool
    {
        $message = "Your adoption booking for *{$petName}* (Booking #{$bookingId}) has been *confirmed*! 🎉";

        return $this->sendBookingNotification($phone, $customerName, $message);
    }

    /**
     * Status Update
     */
    public function sendStatusUpdate(string $phone, string $customerName, string $petName, string $status, string $bookingType = 'booking'): bool
    {
        $statusMsg = match ($status) {
            'confirmed'  => "confirmed ✅",
            'completed'  => "completed 🎉",
            'cancelled'  => "cancelled ❌",
            default      => "updated to {$status}",
        };

        $message = "Your {$bookingType} for *{$petName}* has been {$statusMsg}";

        return $this->sendBookingNotification($phone, $customerName, $message);
    }
    private function formatPhone(string $phone): string
    {
        // 1. Remove all non-digits
        $phone = preg_replace('/\D+/', '', $phone);

        // 2. Remove leading zeros (multiple bhi ho sakte hain)
        $phone = ltrim($phone, '0');

        // 3. Agar number already 91 se start ho raha hai (12 digit)
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            return $phone;
        }

        // 4. Agar 10 digit ka number hai → India assume karke 91 add karo
        if (strlen($phone) === 10) {
            return '91' . $phone;
        }

        // 5. Agar 11 digit hai aur 0 se start tha (jaise 0987...) → trim ke baad 10 digit ban gaya hoga
        if (strlen($phone) === 11 && str_starts_with($phone, '9')) {
            return '9' . substr($phone, 1); // fallback safe
        }

        // 6. Final fallback (jo hai wo return karo but log kar do)
        Log::warning('फोन नंबर format unclear', ['input' => $phone]);

        return $phone;
    }
    /**
     * Core method (API call)
     */
    private function sendMessage(string $phone, string $message, string $name): bool
    {
       $phone = $this->formatPhone($phone); // ✅ yahi use karo

        try {
            $response = Http::timeout(10)->withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                "session"     => $this->session,
                "to"          => $phone,
                "message"     => $message,
                "contactName" => $name,
            ]);

            Log::info('WAA DEBUG', [
                'phone' => $phone,
                'response_status' => $response->status(),
                'response' => $response->body()
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('WAA ERROR', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Generate OTP
     */
    public static function generateOtp(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
}
