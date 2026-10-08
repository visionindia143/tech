<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TestBooking;
use App\Services\MSG91Service;
use App\Services\WaaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestBookingController extends Controller
{
    public function __construct(private MSG91Service $msg91, private WaaService $waa) {}

    public function index(Request $request)
    {
        $query = TestBooking::with(['testType', 'city'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('pet_name', 'like', "%{$search}%")
                    ->orWhere('pet_type', 'like', "%{$search}%")
                    ->orWhereHas('testType', function ($testQuery) use ($search) {
                        $testQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->paginate(15);

        return view('admin.test-bookings.index', compact('bookings'));
    }

    public function show(TestBooking $booking)
    {
        $booking->load(['testType', 'city']);

        return view('admin.test-bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, TestBooking $booking)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $oldStatus = $booking->status;
        $booking->update($data);

        if ($oldStatus !== $request->status && Setting::get('diagnostic_notify_enabled', '0') === '1') {
            try {
                $sent = false;

                if (Setting::get('wa_enabled', '0') === '1') {
                    $sent = $this->waa->sendStatusUpdate(
                        $booking->phone,
                        $booking->name,
                        $booking->pet_name,
                        $request->status,
                        'diagnostic booking'
                    );
                } elseif (Setting::get('msg91_enabled', '0') === '1') {
                    $sent = $this->msg91->sendStatusUpdate(
                        $booking->phone,
                        $booking->name,
                        $booking->pet_name,
                        $request->status,
                        'diagnostic booking'
                    );
                }

                if ($sent) {
                    $booking->update(['whatsapp_sent' => true]);
                }
            } catch (\Throwable $e) {
                Log::error('WhatsApp diagnostic status update failed', ['error' => $e->getMessage()]);
            }
        }

        return back()->with('success', 'Diagnostic booking status updated successfully!');
    }
}
