<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdoptionBooking;
use App\Models\Setting;
use App\Services\MSG91Service;
use App\Services\WaaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdoptionBookingController extends Controller
{
    public function __construct(private MSG91Service $msg91, private WaaService $waa) {}

    public function index(Request $request)
    {
        $query = AdoptionBooking::with('pet')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->paginate(20);
        return view('admin.adoption-bookings.index', compact('bookings'));
    }

    public function show(AdoptionBooking $adoptionBooking)
    {
        $adoptionBooking->load('pet');
        return view('admin.adoption-bookings.show', ['booking' => $adoptionBooking]);
    }

   public function updateStatus(Request $request, AdoptionBooking $adoptionBooking)
{
    $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled']);

    $oldStatus = $adoptionBooking->status;
    $adoptionBooking->update(['status' => $request->status]);

    // ✅ IMPORTANT: relation load karo
    $adoptionBooking->load('pet');

    if ($oldStatus !== $request->status && Setting::get('adoption_notify_enabled', '0') === '1') {
        try {
            Log::info('ADOPTION STATUS TRIGGERED', [
                'old' => $oldStatus,
                'new' => $request->status,
                'phone' => $adoptionBooking->phone
            ]);

            $sent = false;

            // ✅ safe pet name
            $petName = optional($adoptionBooking->pet)->name ?? 'your pet';

            // ❌ phone empty hai to skip
            if (empty($adoptionBooking->phone)) {
                Log::warning('Adoption phone missing');
            } else {

                // ✅ WA first
                if ($this->waa->isEnabled()) {
                    $sent = $this->waa->sendStatusUpdate(
                        $adoptionBooking->phone,
                        $adoptionBooking->name,
                        $petName,
                        $request->status,
                        'adoption booking'
                    );
                }

                // ✅ fallback MSG91 (IMPORTANT FIX)
                if (!$sent && Setting::get('msg91_enabled', '0') === '1') {
                    $sent = $this->msg91->sendStatusUpdate(
                        $adoptionBooking->phone,
                        $adoptionBooking->name,
                        $petName,
                        $request->status,
                        'adoption booking'
                    );
                }
            }

            if ($sent) {
                $adoptionBooking->update(['whatsapp_sent' => true]);
            }

        } catch (\Throwable $e) {
            Log::error('WhatsApp adoption status update failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    // Pet status update (ye sahi hai)
    if ($adoptionBooking->pet) {
        if ($request->status === 'completed') {
            $adoptionBooking->pet->update(['status' => 'adopted']);
        } elseif ($request->status === 'cancelled') {
            $adoptionBooking->pet->update(['status' => 'available']);
        }
    }

    return back()->with('success', 'Booking status updated successfully!');
}
}
