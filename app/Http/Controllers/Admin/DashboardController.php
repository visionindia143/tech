<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Blog, VaccinationBooking, TestBooking, AdoptionBooking, AppointmentBooking, Contact, PetAdoption};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'blogs'           => Blog::count(),
            'appointments'    => AppointmentBooking::count(),
            'tests'           => TestBooking::count(),
            'adoptions'       => AdoptionBooking::count(),
            'vaccinations'    => VaccinationBooking::count(),
            'pets_available'  => PetAdoption::where('status', 'available')->where('is_active', true)->count(),
            'messages'        => Contact::count(),
            'unread_messages' => Contact::where('is_read', false)->count(),
            'pending'         =>
                AppointmentBooking::where('status', 'pending')->count() +
                TestBooking::where('status', 'pending')->count() +
                AdoptionBooking::where('status', 'pending')->count() +
                VaccinationBooking::where('status', 'pending')->count(),
        ];

        $appointments = AppointmentBooking::latest()->take(5)->get()->map(function ($item) {
            $item->type = 'Appointment';
            return $item;
        });

        $tests = TestBooking::latest()->take(5)->get()->map(function ($item) {
            $item->type = 'Test';
            return $item;
        });

        $adoptions = AdoptionBooking::with('pet')->latest()->take(5)->get()->map(function ($item) {
            $item->type = 'Adoption';
            return $item;
        });

        $vaccinations = VaccinationBooking::latest()->take(5)->get()->map(function ($item) {
            $item->type = 'Vaccination';
            return $item;
        });

        $recentBookings = collect()
            ->merge($appointments)
            ->merge($tests)
            ->merge($adoptions)
            ->merge($vaccinations)
            ->where('status', 'pending')
            ->sortByDesc('created_at')
            ->take(10);

        $recentMessages = Contact::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentMessages'));
    }

    public function allPending()
    {
        $appointments = AppointmentBooking::where('status', 'pending')->latest()->get()->map(function ($item) {
            $item->type = 'Appointment';
            $item->link = route('admin.appointment-bookings.show', $item->id);
            return $item;
        });

        $tests = TestBooking::where('status', 'pending')->latest()->get()->map(function ($item) {
            $item->type = 'Test';
            $item->link = route('admin.test-bookings.show', $item->id);
            return $item;
        });

        $adoptions = AdoptionBooking::with('pet')->where('status', 'pending')->latest()->get()->map(function ($item) {
            $item->type = 'Adoption';
            $item->link = route('admin.adoption-bookings.show', $item->id);
            return $item;
        });

        $vaccinations = VaccinationBooking::where('status', 'pending')->latest()->get()->map(function ($item) {
            $item->type = 'Vaccination';
            $item->link = route('admin.bookings.show', $item->id);
            return $item;
        });

        $allPending = collect()
            ->merge($appointments)
            ->merge($tests)
            ->merge($adoptions)
            ->merge($vaccinations)
            ->sortByDesc('created_at');

        $counts = [
            'appointments' => $appointments->count(),
            'tests'        => $tests->count(),
            'adoptions'    => $adoptions->count(),
            'vaccinations' => $vaccinations->count(),
            'total'        => $allPending->count(),
        ];

        return view('admin.pending-all', compact('allPending', 'counts'));
    }
}
