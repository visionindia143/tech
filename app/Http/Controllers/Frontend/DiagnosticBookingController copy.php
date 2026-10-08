<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\TestBookingNotification;
use App\Models\{City, PetType, TestBooking, TestType};
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiagnosticBookingController extends Controller
{
    public function index()
    {
        $cities = City::active()->get();
        $testTypes = TestType::active()->orderBy('category')->orderBy('name')->get();
        $petTypes = PetType::active()->get();
        $categories = $testTypes->pluck('category')->filter()->unique()->values();

        return view('frontend.diagnostic-booking', compact('cities', 'testTypes', 'petTypes', 'categories'));
    }

    public function availableTests()
    {
        $tests = TestType::active()->orderBy('category')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'tests' => $tests->map(fn ($test) => [
                'id' => $test->id,
                'name' => $test->name,
                'category' => $test->category,
                'price' => $test->price,
                'duration_minutes' => $test->duration_minutes,
                'description' => $test->description,
            ])->values(),
        ]);
    }

    public function availablePetTypes()
    {
        $petTypes = PetType::active()->get();

        return response()->json([
            'success' => true,
            'pet_types' => $petTypes->map(fn ($petType) => [
                'id' => $petType->id,
                'name' => $petType->name,
                'icon' => $petType->icon,
            ])->values(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'pet_type' => 'required|string|max:100',
            'pet_name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0|max:120',
            'gender' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'test_type_id' => 'required|exists:test_types,id',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'nullable|string|max:20',
            'city_id' => 'required|exists:cities,id',
            'medical_history' => 'nullable|string|max:2000',
            'symptoms' => 'nullable|string|max:2000',
            'message' => 'nullable|string|max:2000',
        ]);

        $testType = TestType::active()->findOrFail($data['test_type_id']);

        $booking = TestBooking::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'pet_type' => $data['pet_type'],
            'pet_name' => $data['pet_name'],
            'age' => $data['age'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'test_type_id' => $testType->id,
            'preferred_date' => $data['preferred_date'],
            'preferred_time' => $data['preferred_time'] ?? null,
            'city_id' => $data['city_id'],
            'medical_history' => $data['medical_history'] ?? null,
            'symptoms' => $data['symptoms'] ?? null,
            'message' => $data['message'] ?? null,
            'payment_status' => 'not_required',
            'status' => 'pending',
        ]);

        try {
            MailService::sendToAdmin(new TestBookingNotification($booking->load(['testType', 'city'])));
        } catch (\Throwable $e) {
            Log::warning('Diagnostic booking notification could not be sent', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your diagnostic test booking has been submitted successfully.',
            'booking_id' => $booking->id,
        ]);
    }
}