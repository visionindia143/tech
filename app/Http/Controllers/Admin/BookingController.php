<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\VaccinationBooking;
use App\Services\MSG91Service;
use App\Services\WaaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function __construct(private MSG91Service $msg91, private WaaService $waa) {}

    public function index(Request $request)
    {
        $query = VaccinationBooking::with(['vaccinationType', 'city'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(VaccinationBooking $booking)
    {
        $booking->load(['vaccinationType', 'city']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, VaccinationBooking $booking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled']);

        $oldStatus = $booking->status;
        $booking->update(['status' => $request->status]);

        if ($oldStatus !== $request->status && Setting::get('booking_notify_enabled', '0') === '1') {
            try {
                $sent = false;

                if (Setting::get('wa_enabled', '0') === '1') {
                    $sent = $this->waa->sendStatusUpdate(
                        $booking->phone,
                        $booking->name,
                        $booking->pet_name,
                        $request->status,
                        'vaccination booking'
                    );
                } elseif (Setting::get('msg91_enabled', '0') === '1') {
                    $sent = $this->msg91->sendStatusUpdate(
                        $booking->phone,
                        $booking->name,
                        $booking->pet_name,
                        $request->status,
                        'vaccination booking'
                    );
                }

                if ($sent) {
                    $booking->update(['whatsapp_sent' => true]);
                }
            } catch (\Throwable $e) {
                Log::error('WhatsApp vaccination status update failed', ['error' => $e->getMessage()]);
            }
        }

        return back()->with('success', 'Booking status updated successfully!');
    }
}
