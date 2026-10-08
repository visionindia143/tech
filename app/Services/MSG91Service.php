<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MSG91Service
{
    private ?string $authKey;
    private string $integratedNumber;
    private string $apiUrl = 'https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/';

    // Template names
    const TEMPLATE_NOTIFICATION = 'lms_universal_notification';
    const TEMPLATE_OTP          = 'login_otp'; // reuse same template for OTP message

    // Namespace for the templates
    const NAMESPACE = '11b36669_320a_4760_9781_092a613d67f7';

    public function __construct()
    {
        // Prefer DB setting (admin-configurable) over env
        $this->authKey          = '491584AbFSibyIO69d776c1P1';
        $this->integratedNumber = '918619218048';
    }

    /**
     * Check if MSG91 is configured and enabled.
     */
    public function isEnabled(): bool
    {
        return Setting::get('msg91_enabled', '0') === '1' && !empty($this->authKey);
    }

    /**
     * Send OTP via WhatsApp.
     * Template: Hello {name}, {message}, Contact: {phone}
     *
     * @param string $context  Human-readable context shown in the OTP message (e.g. 'adoption booking', 'vaccination booking')
     */
    public function sendOtp(string $toPhone, string $customerName, string $otp, string $context = 'booking'): bool
    {
        $contactPhone = Setting::get('site_phone', '');
        $message = "Your OTP for {$context}: *{$otp}*";

      return $this->sendTemplate(
    phone: $toPhone,
    body1: $otp,     // ✅ OTP
    body2: $otp,     // ✅ OTP (button के लिए)
    body3: ''        // unused
);
    }

    /**
     * Send booking confirmation/status notification.
     */
    public function sendBookingNotification(string $toPhone, string $customerName, string $message): bool
    {
        $contactPhone = Setting::get('site_phone', '');

        return $this->sendTemplate(
            phone: $toPhone,
            body1: $customerName,
            body2: $message,
            body3: $contactPhone,
        );
    }

    /**
     * Send booking confirmed notification.
     */
    public function sendBookingConfirmed(string $toPhone, string $customerName, string $petName, int $bookingId): bool
    {
        $message = "Your adoption booking for *{$petName}* (Booking #$bookingId) has been *confirmed*! Our team will contact you shortly to complete the adoption process.";
        return $this->sendBookingNotification($toPhone, $customerName, $message);
    }

    /**
     * Send booking status update.
     */
    public function sendStatusUpdate(string $toPhone, string $customerName, string $petName, string $status, string $bookingType = 'booking'): bool
    {
        $statusMsg = match ($status) {
            'confirmed'  => "confirmed ✅",
            'completed'  => "completed 🎉",
            'cancelled'  => "cancelled ❌ Please contact us if you have any questions.",
            default      => "updated to: {$status}",
        };

        $message = "Your {$bookingType} for *{$petName}* has been {$statusMsg}";
        return $this->sendBookingNotification($toPhone, $customerName, $message);
    }

    /**
     * Core method: sends a WhatsApp template message.
     */
    private function sendTemplate(string $phone, string $body1, string $body2, string $body3): bool
    {
        if (empty($this->authKey)) {
            Log::warning('MSG91: authkey not configured');
            return false;
        }

        // Sanitize phone: keep digits only and normalize Indian mobile numbers.
        $phone = preg_replace('/\\D+/', '', $phone);
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }
 $payload = [
            'integrated_number' => $this->integratedNumber,
            'content_type'      => 'template',
            'payload'           => [
                'messaging_product' => 'whatsapp',
                'type'              => 'template',
                'template'          => [
                    'name'      => self::TEMPLATE_OTP,
                    'language'  => [
                        'code'   => 'en',
                        'policy' => 'deterministic',
                    ],
                    'namespace' => self::NAMESPACE,
                    'to_and_components' => [
                        [
                            'to' => [$phone],
                            'components' => [
                                'body_1' => [
                                    'type'  => 'text',
                                    'value' => $body1, // OTP
                                ],
                                'button_1' => [
                                    'subtype' => 'url',
                                    'type'    => 'text',
                                    'value'   => $body2, // OTP or URL
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = Http::asJson()
                ->acceptJson()
                ->withHeaders([
                    'authkey' => $this->authKey,
                ])
                ->post($this->apiUrl, $payload);

            // 🔥 DEBUG (देखने के लिए)
            Log::info('MSG91 DEBUG', [
                'payload'  => $payload,
                'response' => $response->body()
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('MSG91 FAILED', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);

            return false;

        } catch (\Throwable $e) {
            Log::error('MSG91 EXCEPTION', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Generate a 6-digit OTP.
     */
    public static function generateOtp(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
}
