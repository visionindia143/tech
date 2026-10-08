<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Blog, VaccinationBooking, TestBooking, AdoptionBooking, AppointmentBooking, Contact, Gallery, Service, City};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'blogs'            => Blog::count(),
            'bookings'         => VaccinationBooking::count() + TestBooking::count() + AppointmentBooking::count(),
            'pending_bookings' => VaccinationBooking::where('status', 'pending')->count()
                                + TestBooking::where('status', 'pending')->count()
                                + AdoptionBooking::where('status', 'pending')->count()
                                + AppointmentBooking::where('status', 'pending')->count(),
            'contacts'         => Contact::where('is_read', false)->count(),
            'galleries'        => Gallery::count(),
            'services'         => Service::count(),
            'cities'           => City::count(),
        ];

        $recentBookings = VaccinationBooking::with(['vaccinationType', 'city'])
            ->orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
