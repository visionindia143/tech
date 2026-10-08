<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\BookingNotification;
use App\Models\{City, VaccinationType, VaccinationBooking, Setting, PetType};
use App\Services\MailService;
use App\Services\MSG91Service;
use App\Services\WaaService;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VaccinationController extends Controller
{
    public function __construct(
        private MSG91Service $msg91,
        private WaaService $waa
    ) {}
    /**
     * Check if OTP verification is required for vaccination bookings.
     * Controlled by the per-module 'booking_otp_enabled' admin setting.
     */
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
    
    private function isOtpRequired(): bool
    {
        return Setting::get('booking_otp_enabled', '0') === '1'
            && $this->getWhatsAppProvider() !== 'none';
    }

    /**
     * Check if payment is required for vaccination bookings.
     * Controlled by the per-module 'booking_payment_enabled' admin setting.
     */
    private function isPaymentEnabled(): bool
    {
        return Setting::get('booking_payment_enabled', '0') === '1';
    }

    /**
     * Get the admin-set booking fee for vaccination.
     */
    private function getBookingFee(): float
    {
        return (float) Setting::get('booking_fee_amount', '0');
    }

    /**
     * Display the vaccination booking form.
     */
    public function index()
    {
        $cities           = City::active()->get();
        $vaccinationTypes = VaccinationType::active()->get();
        $petTypes         = PetType::active()->get();
        $otpRequired      = $this->isOtpRequired();
        $paymentEnabled   = $this->isPaymentEnabled();
        $bookingFee       = $this->getBookingFee();
        $razorpayKey      = Setting::get('razorpay_key_id', '');

        return view('frontend.vaccination', compact(
            'cities',
            'vaccinationTypes',
            'petTypes',
            'otpRequired',
            'paymentEnabled',
            'bookingFee',
            'razorpayKey'
        ));
    }

    /**
     * Send OTP to client's phone via MSG91 WhatsApp.
     */
    public function sendOtp(Request $request)
    {
        if (!$this->isOtpRequired()) {
            return response()->json(['success' => false, 'message' => 'OTP verification is not available.'], 400);
        }

        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'name'  => 'required|string|max:100',
        ]);

        $otp   = MSG91Service::generateOtp();
        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        session([
            "vac_otp_{$phone}"         => $otp,
            "vac_otp_{$phone}_sent_at" => now()->timestamp,
            "vac_otp_{$phone}_name"    => $request->name,
        ]);

        $sent = $this->msg91->sendOtp($phone, $request->name, $otp, 'vaccination booking');

        if ($sent) {
            return response()->json(['success' => true, 'message' => 'OTP sent to your WhatsApp number.']);
        }

        if (config('app.env') === 'local') {
            return response()->json(['success' => true, 'message' => "OTP sent. (Dev mode OTP: {$otp})"]);
        }

        return response()->json(['success' => false, 'message' => 'Failed to send OTP. Please try again.'], 500);
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

        $phone    = preg_replace('/[^0-9]/', '', $request->phone);
        $stored   = session("vac_otp_{$phone}");
        $sentAt   = session("vac_otp_{$phone}_sent_at");

        if (!$stored) {
            return response()->json(['success' => false, 'message' => 'OTP expired or not sent. Please request again.'], 422);
        }

        if (now()->timestamp - $sentAt > 600) {
            session()->forget(["vac_otp_{$phone}", "vac_otp_{$phone}_sent_at"]);
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new one.'], 422);
        }

        if ($request->otp !== $stored) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please check and try again.'], 422);
        }

        session(["vac_otp_{$phone}_verified" => true]);

        return response()->json(['success' => true, 'message' => 'Phone verified successfully!']);
    }

    /**
     * Create vaccination booking via AJAX (with or without payment).
     */
    public function store(Request $request)
    {
        if (!RecaptchaService::verify($request->input('g_recaptcha_response'))) {
            return response()->json(['success' => false, 'message' => 'Please complete the reCAPTCHA verification.'], 422);
        }

        $request->validate([
            'name'                => 'required|string|max:255',
            'phone'               => 'required|string|max:20',
            'email'               => 'required|email|max:255',
            'pet_type'            => 'required|string|max:100',
            'pet_name'            => 'required|string|max:255',
            'vaccination_type_id' => 'required|exists:vaccination_types,id',
            'preferred_date'      => 'required|date|after:today',
            'city_id'             => 'required|exists:cities,id',
            'message'             => 'nullable|string|max:1000',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        // Enforce OTP verification if required
        if ($this->isOtpRequired() && !session("vac_otp_{$phone}_verified")) {
            return response()->json(['success' => false, 'message' => 'Phone number not verified. Please verify OTP first.'], 422);
        }

        $paymentEnabled = $this->isPaymentEnabled();
        $bookingFee     = $this->getBookingFee();

        $booking = VaccinationBooking::create([
            'name'                => $request->name,
            'phone'               => $request->phone,
            'email'               => $request->email,
            'pet_type'            => $request->pet_type,
            'pet_name'            => $request->pet_name,
            'vaccination_type_id' => $request->vaccination_type_id,
            'preferred_date'      => $request->preferred_date,
            'city_id'             => $request->city_id,
            'message'             => $request->message,
            'otp_verified_at'     => $this->isOtpRequired() ? now() : null,
            'payment_status'      => ($paymentEnabled && $bookingFee > 0) ? 'pending' : 'not_required',
            'amount'              => ($paymentEnabled && $bookingFee > 0) ? $bookingFee : null,
            'status'              => 'pending',
        ]);

        // Clear OTP session
        session()->forget(["vac_otp_{$phone}", "vac_otp_{$phone}_sent_at", "vac_otp_{$phone}_verified", "vac_otp_{$phone}_name"]);

        if ($paymentEnabled && $bookingFee > 0) {
            $order = $this->createRazorpayOrder($booking);

            if ($order) {
                $booking->update(['razorpay_order_id' => $order['id']]);
                return response()->json([
                    'success'          => true,
                    'requires_payment' => true,
                    'booking_id'       => $booking->id,
                    'order_id'         => $order['id'],
                    'amount'           => (int)($bookingFee * 100), // paise
                    'currency'         => 'INR',
                    'customer_name'    => $booking->name,
                    'customer_email'   => $booking->email,
                    'customer_phone'   => $booking->phone,
                ]);
            }

            // Order creation failed — cancel so admin can diagnose
            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);

            return response()->json([
                'success' => false,
                'message' => 'Payment gateway error. Please verify your Razorpay API keys in Admin → Settings and try again.',
            ], 500);
        }

        // No payment needed (fee is 0 or payment is disabled) — send WhatsApp + admin email
        $this->sendConfirmationWhatsApp($booking);
        MailService::sendToAdmin(new BookingNotification($booking));

        return response()->json([
            'success'          => true,
            'requires_payment' => false,
            'message'          => 'Your vaccination booking has been submitted! We will contact you shortly.',
            'booking_id'       => $booking->id,
        ]);
    }

    /**
     * Verify Razorpay payment signature and confirm vaccination booking.
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'booking_id'          => 'required|exists:vaccination_bookings,id',
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $booking   = VaccinationBooking::findOrFail($request->booking_id);
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

        $fresh = $booking->fresh();
        $this->sendConfirmationWhatsApp($fresh);
        MailService::sendToAdmin(new BookingNotification($fresh));

        return response()->json([
            'success'    => true,
            'message'    => 'Payment successful! Your vaccination booking is confirmed.',
            'booking_id' => $booking->id,
        ]);
    }

    private function createRazorpayOrder(VaccinationBooking $booking): ?array
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
                    'receipt'  => 'vaccination_' . $booking->id,
                    'notes'    => ['booking_id' => $booking->id],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Razorpay vaccination order failed', ['response' => $response->body()]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Razorpay exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function sendConfirmationWhatsApp(VaccinationBooking $booking): void
    {
        try {
            $message = "Your vaccination booking (Booking #{$booking->id}) for *{$booking->pet_name}* has been confirmed! Our team will contact you shortly to confirm the appointment.";
            $sent = $this->msg91->sendBookingNotification($booking->phone, $booking->name, $message);
            if ($sent) {
                $booking->update(['whatsapp_sent' => true]);
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp vaccination notification failed', ['error' => $e->getMessage()]);
        }
    }
}
