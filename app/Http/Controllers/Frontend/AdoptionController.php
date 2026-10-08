<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\AdoptionBookingNotification;
use App\Models\{PetAdoption, AdoptionBooking, Setting};
use App\Services\MailService;
use App\Services\MSG91Service;
use App\Services\RecaptchaService;
use App\Services\WaaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class AdoptionController extends Controller
{
    private MSG91Service $msg91;
    private WaaService $waa;

    public function __construct(MSG91Service $msg91, WaaService $waa)
    {
        $this->msg91 = $msg91;
        $this->waa   = $waa;
    }

    private function getWhatsAppProvider(): string
    {
        if (Setting::get('wa_enabled') === '1') {
            return 'wa';
        }

        if (Setting::get('msg91_enabled') === '1') {
            return 'msg91';
        }

        return 'none';
    }


    /**
     * Check if OTP verification is required for adoption bookings.
     * Controlled by the per-module 'adoption_otp_enabled' admin setting.
     */
    private function isOtpRequired(): bool
    {
        return Setting::get('adoption_otp_enabled', '0') === '1'
            && $this->getWhatsAppProvider() !== 'none';
    }
    /**
     * Check if payment is required for adoption bookings.
     * Reads 'adoption_payment_enabled'; falls back to legacy 'payment_enabled'
     * so settings saved before the per-module split still work.
     */
    private function isPaymentEnabled(): bool
    {
        $perModule = Setting::get('adoption_payment_enabled');
        if ($perModule !== null) {
            return $perModule === '1';
        }
        // Legacy fallback (settings saved before per-module split)
        return Setting::get('payment_enabled', '0') === '1';
    }

    /**
     * List all available pets for adoption.
     */
    public function index(Request $request)
    {
        $query = PetAdoption::active()->available();

        if ($request->filled('type')) {
            $query->where('pet_type', $request->type);
        }
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $pets = $query->paginate(12);
        $petTypes = PetAdoption::active()->available()->distinct()->pluck('pet_type');

        return view('frontend.adoption', compact('pets', 'petTypes'));
    }

    /**
     * Show a single pet detail page with booking form.
     */
    public function show(string $slug)
    {
        $pet            = PetAdoption::active()->where('slug', $slug)->firstOrFail();
        $paymentEnabled = $this->isPaymentEnabled();
        $razorpayKey    = Setting::get('razorpay_key_id', '');
        $otpRequired    = $this->isOtpRequired();

        return view('frontend.adoption-detail', compact('pet', 'paymentEnabled', 'razorpayKey', 'otpRequired'));
    }

    /**
     * Send OTP to client's phone via MSG91 WhatsApp.
     */
    public function sendOtp(Request $request)
    {
        if (!$this->isOtpRequired()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP verification is disabled.'
            ], 400);
        }

        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'name'  => 'required|string|max:100',
        ]);

        $otp   = MSG91Service::generateOtp(); // common OTP
        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        // ✅ Store OTP
        session([
            "otp_{$phone}"          => $otp,
            "otp_{$phone}_sent_at"  => now()->timestamp,
            "otp_{$phone}_name"     => $request->name,
        ]);

        $provider = $this->getWhatsAppProvider();
        $sent = false;

        // ✅ Provider based send
        if ($provider === 'wa') {

            if (!$this->waa->isEnabled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'WA API not configured properly'
                ], 500);
            }

            $sent = $this->waa->sendOtp(
                $phone,
                $request->name,
                $otp,
                'pet adoption booking'
            );
        } elseif ($provider === 'msg91') {

            $sent = $this->msg91->sendOtp(
                $phone,
                $request->name,
                $otp,
                'pet adoption booking'
            );
        }

        // ✅ Debug (VERY IMPORTANT)
        Log::info('OTP DEBUG', [
            'provider' => $provider,
            'phone' => $phone,
            'otp' => $otp,
            'sent' => $sent
        ]);

        if ($sent) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP.'
        ], 500);
    }


    /**
     * Verify OTP entered by the client.
     */
    public function verifyOtp(Request $request)
    {
        if (!$this->isOtpRequired()) {
            return response()->json(['success' => false, 'message' => 'OTP verification is not required.'], 400);
        }

        $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|string|size:6',
        ]);

        $phone       = preg_replace('/[^0-9]/', '', $request->phone);
        $sessionOtp  = session("otp_{$phone}");
        $sentAt      = session("otp_{$phone}_sent_at");

        if (!$sessionOtp) {
            return response()->json(['success' => false, 'message' => 'OTP expired or not sent. Please request again.'], 422);
        }

        // Check expiry (10 minutes)
        if (now()->timestamp - $sentAt > 600) {
            session()->forget(["otp_{$phone}", "otp_{$phone}_sent_at"]);
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new one.'], 422);
        }

        if ($request->otp !== $sessionOtp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please check and try again.'], 422);
        }

        // Mark as verified in session
        session(["otp_{$phone}_verified" => true]);

        return response()->json(['success' => true, 'message' => 'Phone verified successfully!']);
    }

    /**
     * Create booking after OTP verified (with or without payment).
     */
    public function book(Request $request)
    {
        if (!RecaptchaService::verify($request->input('g_recaptcha_response'))) {
            return response()->json(['success' => false, 'message' => 'Please complete the reCAPTCHA verification.'], 422);
        }

        $request->validate([
            'pet_adoption_id' => 'required|exists:pet_adoptions,id',
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'email'           => 'required|email|max:255',
            'address'         => 'nullable|string|max:500',
            'city'            => 'nullable|string|max:100',
            'notes'           => 'nullable|string|max:1000',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        // Ensure OTP was verified (only if OTP is required)
        if ($this->isOtpRequired() && !session("otp_{$phone}_verified")) {
            return response()->json(['success' => false, 'message' => 'Phone number not verified. Please verify OTP first.'], 422);
        }

        $pet            = PetAdoption::findOrFail($request->pet_adoption_id);
        $paymentEnabled = $this->isPaymentEnabled();

        // If payment is enabled, ensure the pet has a valid adoption fee
        if ($paymentEnabled && (!isset($pet->adoption_fee) || $pet->adoption_fee === null || $pet->adoption_fee <= 0)) {
            Log::warning('Pet adoption fee not configured or invalid', [
                'pet_id' => $pet->id,
                'adoption_fee' => $pet->adoption_fee,
                'payment_enabled' => $paymentEnabled
            ]);
            return response()->json(['success' => false, 'message' => 'This pet does not have a valid adoption fee configured.'], 400);
        }

        $booking = AdoptionBooking::create([
            'pet_adoption_id'  => $pet->id,
            'name'             => $request->name,
            'phone'            => $request->phone,
            'email'            => $request->email,
            'address'          => $request->address,
            'city'             => $request->city,
            'notes'            => $request->notes,
            'otp_verified_at'  => now(),
            'payment_status'   => ($paymentEnabled && $pet->adoption_fee > 0) ? 'pending' : 'not_required',
            'amount'           => ($paymentEnabled && $pet->adoption_fee > 0) ? $pet->adoption_fee : null,
            'status'           => 'pending',
        ]);

        // Reserve the pet
        if ($pet->status === 'available') {
            $pet->update(['status' => 'reserved']);
        }

        // Clear OTP session
        session()->forget(["otp_{$phone}", "otp_{$phone}_sent_at", "otp_{$phone}_verified", "otp_{$phone}_name"]);

        if ($paymentEnabled && $pet->adoption_fee > 0) {
            Log::info('Creating Razorpay order for adoption booking', [
                'booking_id' => $booking->id,
                'pet_id' => $pet->id,
                'amount' => $pet->adoption_fee,
                'payment_enabled' => $paymentEnabled
            ]);

            // Create Razorpay order
            $order = $this->createRazorpayOrder($booking);

            if ($order) {
                $booking->update(['razorpay_order_id' => $order['id']]);
                return response()->json([
                    'success'          => true,
                    'requires_payment' => true,
                    'booking_id'       => $booking->id,
                    'order_id'         => $order['id'],
                    'amount'           => (int)($pet->adoption_fee * 100), // paise
                    'currency'         => 'INR',
                    'pet_name'         => $pet->name,
                    'customer_name'    => $booking->name,
                    'customer_email'   => $booking->email,
                    'customer_phone'   => $booking->phone,
                ]);
            }

            // Order creation failed — roll back the reservation so the pet stays available
            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            $pet->update(['status' => 'available']);

            return response()->json([
                'success' => false,
                'message' => 'Payment gateway error. Please verify your Razorpay API keys in Admin → Settings → Razorpay API Keys section and try again.',
            ], 500);
        }

        // No payment needed — send confirmation WhatsApp + admin email
        $this->sendConfirmationWhatsApp($booking, $pet);
        MailService::sendToAdmin(new AdoptionBookingNotification($booking->load('pet')));

        return response()->json([
            'success'          => true,
            'requires_payment' => false,
            'message'          => 'Booking submitted successfully! We will contact you shortly.',
            'booking_id'       => $booking->id,
        ]);
    }

    /**
     * Verify Razorpay payment signature and confirm booking.
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'booking_id'          => 'required|exists:adoption_bookings,id',
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $booking = AdoptionBooking::with('pet')->findOrFail($request->booking_id);

        $keySecret = Setting::get('razorpay_key_secret', '');
        $generated = hash_hmac('sha256', $request->razorpay_order_id . '|' . $request->razorpay_payment_id, $keySecret);

        if ($generated !== $request->razorpay_signature) {
            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 422);
        }

        $booking->update([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'payment_status'      => 'paid',
            'status'              => 'confirmed',
        ]);

        $freshBooking = $booking->fresh()->load('pet');
        $this->sendConfirmationWhatsApp($freshBooking, $freshBooking->pet);
        MailService::sendToAdmin(new AdoptionBookingNotification($freshBooking));

        return response()->json([
            'success'    => true,
            'message'    => 'Payment successful! Your adoption booking is confirmed.',
            'booking_id' => $booking->id,
        ]);
    }

    private function createRazorpayOrder(AdoptionBooking $booking): ?array
    {
        $keyId     = Setting::get('razorpay_key_id', '');
        $keySecret = Setting::get('razorpay_key_secret', '');

        if (!$keyId || !$keySecret) {
            Log::warning('Razorpay keys not configured');
            return null;
        }

        try {
            $response = Http::withBasicAuth($keyId, $keySecret)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount'   => (int)($booking->amount * 100),
                    'currency' => 'INR',
                    'receipt'  => 'adoption_' . $booking->id,
                    'notes'    => ['booking_id' => $booking->id],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Razorpay adoption order failed', ['response' => $response->body()]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Razorpay exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function sendConfirmationWhatsApp(AdoptionBooking $booking, PetAdoption $pet): void
    {
        try {
            $provider = $this->getWhatsAppProvider();
            $sent = false;

            if ($provider === 'wa') {
                $sent = $this->waa->sendBookingConfirmed(
                    $booking->phone,
                    $booking->name,
                    $pet->name,
                    $booking->id
                );
            } elseif ($provider === 'msg91') {
                $sent = $this->msg91->sendBookingConfirmed(
                    $booking->phone,
                    $booking->name,
                    $pet->name,
                    $booking->id
                );
            }

            if ($sent) {
                $booking->update(['whatsapp_sent' => true]);
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp failed', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
