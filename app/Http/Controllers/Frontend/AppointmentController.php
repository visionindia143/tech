<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentBookingNotification;
use App\Models\{City, Service, AppointmentBooking, AppointmentType, Setting, PetType};
use App\Services\MailService;
use App\Services\MSG91Service;
use App\Services\RecaptchaService;
use App\Services\WaaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
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



    private function isOtpRequired(): bool
    {
        return Setting::get('appt_otp_enabled', '0') === '1'
            && $this->getWhatsAppProvider() !== 'none';
    }

    private function isPaymentEnabled(): bool
    {
        return Setting::get('appt_payment_enabled', '0') === '1';
    }

    private function getBookingFee(): float
    {
        return (float) Setting::get('appt_fee_amount', '0');
    }

    public function index()
    {
        $cities         = City::active()->get();
        $services = Service::active()->get();
        $apptTypes = AppointmentType::where('is_active', 1)->get();

        $petTypes       = PetType::active()->get();
        $otpRequired    = $this->isOtpRequired();
        $paymentEnabled = $this->isPaymentEnabled();
        $bookingFee     = $this->getBookingFee();
        $razorpayKey    = Setting::get('razorpay_key_id', '');

        return view('frontend.appointment', compact(
            'cities',
            'services',
            'apptTypes',
            'petTypes',
            'otpRequired',
            'paymentEnabled',
            'bookingFee',
            'razorpayKey'
        ));
    }

    public function show(AppointmentBooking $appointmentBooking)
    {
        $appointmentBooking->load(['service', 'city']);
        return view('frontend.appointment-show', ['booking' => $appointmentBooking]);
    }

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

        $otp   = WaaService::generateOtp();
        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        session([
            "appt_otp_{$phone}"         => $otp,
            "appt_otp_{$phone}_sent_at" => now()->timestamp,
            "appt_otp_{$phone}_name"    => $request->name,
        ]);

        $provider = $this->getWhatsAppProvider();
        $sent = false;

        if ($provider === 'wa') {
            $sent = $this->waa->sendOtp($phone, $request->name, $otp, 'appointment booking');
        } elseif ($provider === 'msg91') {
            $sent = $this->msg91->sendOtp($phone, $request->name, $otp, 'appointment booking');
        }

        if ($sent) {
            return response()->json(['success' => true, 'message' => 'OTP sent successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to send OTP.'], 500);
    }

    public function verifyOtp(Request $request)
    {
        if (!$this->isOtpRequired()) {
            return response()->json(['success' => false, 'message' => 'OTP verification is not required.'], 400);
        }

        $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|string|size:6',
        ]);

        $phone  = preg_replace('/[^0-9]/', '', $request->phone);
        $stored = session("appt_otp_{$phone}");
        $sentAt = session("appt_otp_{$phone}_sent_at");

        if (!$stored) {
            return response()->json(['success' => false, 'message' => 'OTP expired or not sent. Please request again.'], 422);
        }

        if (now()->timestamp - $sentAt > 600) {
            session()->forget(["appt_otp_{$phone}", "appt_otp_{$phone}_sent_at"]);
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new one.'], 422);
        }

        if ($request->otp !== $stored) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please check and try again.'], 422);
        }

        session(["appt_otp_{$phone}_verified" => true]);

        return response()->json(['success' => true, 'message' => 'Phone verified successfully!']);
    }

    public function store(Request $request)
    {
        if (!RecaptchaService::verify($request->input('g_recaptcha_response'))) {
            return response()->json(['success' => false, 'message' => 'Please complete the reCAPTCHA verification.'], 422);
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'pet_type'       => 'required|string|max:100',
            'pet_name'       => 'required|string|max:255',
            'service_id' => 'nullable|exists:services,id|required_without:appointment_type_id',
            'appointment_type_id' => 'nullable|exists:appointment_types,id|required_without:service_id',

            'preferred_date' => 'required|date|after:today',
            'city_id'        => 'required|exists:cities,id',
            'message'        => 'nullable|string|max:1000',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        if ($this->isOtpRequired() && !session("appt_otp_{$phone}_verified")) {
            return response()->json(['success' => false, 'message' => 'Phone number not verified. Please verify OTP first.'], 422);
        }

        $paymentEnabled = $this->isPaymentEnabled();
        $bookingFee     = $this->getBookingFee();

        $booking = AppointmentBooking::create([
            'name'           => $request->name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'pet_type'       => $request->pet_type,
            'pet_name'       => $request->pet_name,
            'service_id'     => $request->service_id,
            'appointment_type_id'     => $request->appointment_type_id,
            'preferred_date' => $request->preferred_date,
            'city_id'        => $request->city_id,
            'message'        => $request->message,
            'otp_verified_at' => $this->isOtpRequired() ? now() : null,
            'payment_status' => ($paymentEnabled && $bookingFee > 0) ? 'pending' : 'not_required',
            'amount'         => ($paymentEnabled && $bookingFee > 0) ? $bookingFee : null,
            'status'         => 'pending',
        ]);

        session()->forget(["appt_otp_{$phone}", "appt_otp_{$phone}_sent_at", "appt_otp_{$phone}_verified", "appt_otp_{$phone}_name"]);

        if ($paymentEnabled && $bookingFee > 0) {
            $order = $this->createRazorpayOrder($booking);

            if ($order) {
                $booking->update(['razorpay_order_id' => $order['id']]);
                return response()->json([
                    'success'          => true,
                    'requires_payment' => true,
                    'booking_id'       => $booking->id,
                    'order_id'         => $order['id'],
                    'amount'           => (int)($bookingFee * 100),
                    'currency'         => 'INR',
                    'customer_name'    => $booking->name,
                    'customer_email'   => $booking->email,
                    'customer_phone'   => $booking->phone,
                ]);
            }

            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway error. Please verify your Razorpay API keys in Admin → Settings and try again.',
            ], 500);
        }

        $this->sendConfirmationWhatsApp($booking);
        MailService::sendToAdmin(new AppointmentBookingNotification($booking));

        return response()->json([
            'success'          => true,
            'requires_payment' => false,
            'message'          => 'Your appointment has been booked! We will contact you shortly.',
            'booking_id'       => $booking->id,
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'booking_id'          => 'required|exists:appointment_bookings,id',
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $booking   = AppointmentBooking::findOrFail($request->booking_id);
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
        MailService::sendToAdmin(new AppointmentBookingNotification($fresh));

        return response()->json([
            'success'    => true,
            'message'    => 'Payment successful! Your appointment is confirmed.',
            'booking_id' => $booking->id,
        ]);
    }

    private function createRazorpayOrder(AppointmentBooking $booking): ?array
    {
        $keyId     = Setting::get('razorpay_key_id', '');
        $keySecret = Setting::get('razorpay_key_secret', '');

        if (!$keyId || !$keySecret) {
            Log::warning('Razorpay keys not configured for appointment booking');
            return null;
        }

        try {
            $response = Http::withBasicAuth($keyId, $keySecret)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount'   => (int)($booking->amount * 100),
                    'currency' => 'INR',
                    'receipt'  => 'appointment_' . $booking->id,
                    'notes'    => ['booking_id' => $booking->id],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Razorpay appointment order failed', ['response' => $response->body()]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Razorpay appointment exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function sendConfirmationWhatsApp(AppointmentBooking $booking): void
    {
        try {
            $message = "Your appointment booking (Booking #{$booking->id}) for *{$booking->pet_name}* has been confirmed!";

            $provider = $this->getWhatsAppProvider();
            $sent = false;

            if ($provider === 'wa') {
                $sent = $this->waa->sendBookingNotification($booking->phone, $booking->name, $message);
            } elseif ($provider === 'msg91') {
                $sent = $this->msg91->sendBookingNotification($booking->phone, $booking->name, $message);
            }

            if ($sent) {
                $booking->update(['whatsapp_sent' => true]);
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp failed', ['error' => $e->getMessage()]);
        }
    }
}
