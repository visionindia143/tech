<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentBooking;
use App\Models\Setting;
use App\Services\MSG91Service;
use App\Services\WaaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppointmentBookingController extends Controller
{
    public function __construct(private MSG91Service $msg91, private WaaService $waa) {}

    public function index(Request $request)
    {
        $query = AppointmentBooking::with(['service', 'appointmentType', 'city'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15);
        return view('admin.appointment-bookings.index', compact('bookings'));
    }

    public function show(AppointmentBooking $appointmentBooking)
    {
        $appointmentBooking->load(['service', 'city']);
        return view('admin.appointment-bookings.show', ['booking' => $appointmentBooking]);
    }

    public function updateStatus(Request $request, AppointmentBooking $appointmentBooking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled']);

        $oldStatus = $appointmentBooking->status;
        $appointmentBooking->update(['status' => $request->status]);

        if ($oldStatus !== $request->status && Setting::get('appt_notify_enabled', '0') === '1') {
            try {
                $sent = false;

                if (Setting::get('wa_enabled', '0') === '1') {
                    $sent = $this->waa->sendStatusUpdate(
                        $appointmentBooking->phone,
                        $appointmentBooking->name,
                        $appointmentBooking->pet_name,
                        $request->status,
                        'appointment booking'
                    );
                } elseif (Setting::get('msg91_enabled', '0') === '1') {
                    $sent = $this->msg91->sendStatusUpdate(
                        $appointmentBooking->phone,
                        $appointmentBooking->name,
                        $appointmentBooking->pet_name,
                        $request->status,
                        'appointment booking'
                    );
                }

                if ($sent) {
                    $appointmentBooking->update(['whatsapp_sent' => true]);
                }
            } catch (\Throwable $e) {
                Log::error('WhatsApp appointment status update failed', ['error' => $e->getMessage()]);
            }
        }

        return back()->with('success', 'Booking status updated successfully!');
    }
}
