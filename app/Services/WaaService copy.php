<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class WaaService
{
    
    private string $apiUrl = 'https://wa.suthartech.com/api/messages/send';
    private string $apiKey = 'wac_live_dcb8af6476e6ee34963cb790cc9e44a9a35f5b653bf619e3';
    private string $session = 'wa_1777124873467_fg8nquc6u';

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
    public function sendStatusUpdate(string $phone, string $customerName, string $petName, string $status): bool
    {
        $statusMsg = match ($status) {
            'confirmed'  => "confirmed ✅",
            'completed'  => "completed 🎉",
            'cancelled'  => "cancelled ❌",
            default      => "updated to {$status}",
        };

        $message = "Your booking for *{$petName}* has been {$statusMsg}";

        return $this->sendBookingNotification($phone, $customerName, $message);
    }

    /**
     * Core method (API call)
     */
    private function sendMessage(string $phone, string $message, string $name): bool
    {
        // Clean number
        $phone = preg_replace('/\D+/', '', $phone);
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        try {
            $response = Http::withHeaders([
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
                'message' => $message,
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
