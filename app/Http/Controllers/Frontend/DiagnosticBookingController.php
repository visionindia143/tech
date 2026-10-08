<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\TestBookingNotification;
use App\Models\Setting;
use App\Models\{City, PetType, TestBooking, TestType};
use App\Services\{MailService, MSG91Service, WaaService, RecaptchaService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Http, Log, DB};

class DiagnosticBookingController extends Controller
{
    /**
     * Injecting Services for OTP and WhatsApp
     */
    public function __construct(
        private MSG91Service $msg91,
        private WaaService $waa
    ) {}

    // --- Core Logic Helpers ---

    private function getWhatsAppProvider(): string
    {
        if (Setting::get('wa_enabled') === '1') return 'wa';
        if (Setting::get('msg91_enabled') === '1') return 'msg91';
        return 'none';
    }

    private function isOtpRequired(): bool
    {
        return Setting::get('diagnostic_otp_enabled', '0') === '1'
            && $this->getWhatsAppProvider() !== 'none';
    }

    private function isPaymentEnabled(): bool
    {
        return Setting::get('diagnostic_payment_enabled', '0') === '1';
    }

    // --- Main Actions ---

    public function availableTests()
    {
        return response()->json([
            'success' => true,
            'tests'   => TestType::active()->orderBy('category')->orderBy('name')->get()->values(),
        ]);
    }

    public function availablePetTypes()
    {
        return response()->json([
            'success'   => true,
            'pet_types' => PetType::active()->get()->values(),
        ]);
    }

    /**
     * Display the diagnostic booking form.
     */
    public function index()
    {
        $cities         = City::active()->get();
        $testTypes      = TestType::active()->orderBy('category')->orderBy('name')->get();
        $petTypes       = PetType::active()->get();
        $categories     = $testTypes->pluck('category')->filter()->unique()->values();
        
        // Settings to pass to Frontend JS
        $otpRequired    = $this->isOtpRequired();
        $paymentEnabled = $this->isPaymentEnabled();
        $razorpayKey    = Setting::get('razorpay_key_id', '');

        return view('frontend.diagnostic-booking', compact(
            'cities', 'testTypes', 'petTypes', 'categories', 
            'otpRequired', 'paymentEnabled', 'razorpayKey'
        ));
    }

    /**
     * Send OTP to client via configured provider.
     */
    public function sendOtp(Request $request)
    {
        if (!$this->isOtpRequired()) {
            return response()->json(['success' => false, 'message' => 'OTP verification is disabled.'], 400);
        }

        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'name'  => 'required|string|max:100',
        ]);

        $otp   = WaaService::generateOtp();
        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        // Save in session with a unique prefix for Diagnostic
        session([
            "diag_otp_{$phone}"         => $otp,
            "diag_otp_{$phone}_sent_at" => now()->timestamp,
            "diag_otp_{$phone}_name"    => $request->name,
        ]);

        $provider = $this->getWhatsAppProvider();
        $sent = false;

        if ($provider === 'wa') {
            $sent = $this->waa->sendOtp($phone, $request->name, $otp, 'diagnostic booking');
        } elseif ($provider === 'msg91') {
            $sent = $this->msg91->sendOtp($phone, $request->name, $otp, 'diagnostic booking');
        }

        if ($sent) {
            return response()->json(['success' => true, 'message' => 'OTP sent successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to send OTP.'], 500);
    }

    /**
     * Verify the entered OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|string|size:6',
        ]);

        $phone  = preg_replace('/[^0-9]/', '', $request->phone);
        $stored = session("diag_otp_{$phone}");
        $sentAt = session("diag_otp_{$phone}_sent_at");

        if (!$stored || (now()->timestamp - $sentAt > 600)) {
            return response()->json(['success' => false, 'message' => 'OTP expired or not found.'], 422);
        }

        if ($request->otp !== $stored) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP.'], 422);
        }

        session(["diag_otp_{$phone}_verified" => true]);

        return response()->json(['success' => true, 'message' => 'Phone verified successfully!']);
    }

    /**
     * Store the booking and handle Payment.
     */
    public function store(Request $request)
    {
        // 1. Recaptcha Check
        if (!RecaptchaService::verify($request->input('g_recaptcha_response'))) {
            return response()->json(['success' => false, 'message' => 'reCAPTCHA verification failed.'], 422);
        }

        // 2. Validation
        $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'email'           => 'required|email|max:255',
            'pet_type'        => 'required|string|max:100',
            'pet_name'        => 'required|string|max:255',
            'test_type_id'    => 'required|exists:test_types,id',
            'preferred_date'  => 'required|date|after:today',
            'city_id'         => 'required|exists:cities,id',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        // 3. OTP Enforcement
        if ($this->isOtpRequired() && !session("diag_otp_{$phone}_verified")) {
            return response()->json(['success' => false, 'message' => 'Please verify your phone via OTP first.'], 422);
        }

        $testType = TestType::findOrFail($request->test_type_id);
        $amount   = (float) $testType->price;

        // 4. Create Booking Entry
        $booking = TestBooking::create(array_merge($request->all(), [
            'amount'          => $amount,
            'payment_status'  => ($this->isPaymentEnabled() && $amount > 0) ? 'pending' : 'not_required',
            'otp_verified_at' => $this->isOtpRequired() ? now() : null,
            'status'          => 'pending',
        ]));

        // Clear OTP Session
        session()->forget(["diag_otp_{$phone}", "diag_otp_{$phone}_sent_at", "diag_otp_{$phone}_verified"]);

        // 5. Handle Razorpay Payment
        if ($this->isPaymentEnabled() && $amount > 0) {
            $order = $this->createRazorpayOrder($booking, $amount);

            if ($order) {
                $booking->update(['razorpay_order_id' => $order['id']]);
                return response()->json([
                    'success'          => true,
                    'requires_payment' => true,
                    'booking_id'       => $booking->id,
                    'order_id'         => $order['id'],
                    'amount'           => (int)($amount * 100), // in paise
                    'currency'         => 'INR',
                    'customer_name'    => $booking->name,
                    'customer_email'   => $booking->email,
                    'customer_phone'   => $booking->phone,
                ]);
            }

            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            return response()->json(['success' => false, 'message' => 'Payment Gateway Error.'], 500);
        }

        // 6. Direct Submission (No Payment)
        $this->finalizeBooking($booking);

        return response()->json([
            'success'          => true,
            'requires_payment' => false,
            'message'          => 'Diagnostic test booking submitted successfully!',
        ]);
    }

    /**
     * Verify Razorpay Payment Signature.
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'booking_id'          => 'required|exists:test_bookings,id',
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $booking   = TestBooking::findOrFail($request->booking_id);
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

        $this->finalizeBooking($booking);

        return response()->json(['success' => true, 'message' => 'Payment successful and booking confirmed!']);
    }

    // --- Private Utility Methods ---

    private function createRazorpayOrder(TestBooking $booking, float $amount): ?array
    {
        $keyId     = Setting::get('razorpay_key_id', '');
        $keySecret = Setting::get('razorpay_key_secret', '');

        if (!$keyId || !$keySecret) return null;

        try {
            $response = Http::withBasicAuth($keyId, $keySecret)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount'   => (int)($amount * 100),
                    'currency' => 'INR',
                    'receipt'  => 'test_booking_' . $booking->id,
                ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable $e) {
            Log::error('Razorpay Order Error: ' . $e->getMessage());
            return null;
        }
    }

    private function finalizeBooking(TestBooking $booking)
    {
        $freshBooking = $booking->load(['testType', 'city']);
        
        // 1. Send WhatsApp
        $this->sendConfirmationWhatsApp($freshBooking);
        
        // 2. Send Email
        try {
            MailService::sendToAdmin(new TestBookingNotification($freshBooking));
        } catch (\Throwable $e) {
            Log::error('Mail Error: ' . $e->getMessage());
        }
    }

    private function sendConfirmationWhatsApp(TestBooking $booking)
    {
        try {
            $message  = "Your diagnostic booking (#{$booking->id}) for *{$booking->testType->name}* is confirmed!";
            $provider = $this->getWhatsAppProvider();
            
            if ($provider === 'wa') {
                $this->waa->sendBookingNotification($booking->phone, $booking->name, $message);
            } elseif ($provider === 'msg91') {
                $this->msg91->sendBookingNotification($booking->phone, $booking->name, $message);
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp Error: ' . $e->getMessage());
        }
    }
}