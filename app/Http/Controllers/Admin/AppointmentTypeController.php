<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentType;
use Illuminate\Http\Request;

class AppointmentTypeController extends Controller
{
    public function index()
    {
        $appointment_types = AppointmentType::latest()->paginate(15);
        return view('admin.appointment-types.index', compact('appointment_types'));
    }

    public function create()
    {
        return view('admin.appointment-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'price'     => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        AppointmentType::create([
            'title'     => $request->title,
            'price'     => $request->price,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.appointment-types.index')
            ->with('success', 'Appointment Type created successfully!');
    }

    public function edit(AppointmentType $appointment_type)
    {
        return view('admin.appointment-types.edit', compact('appointment_type'));
    }

    public function update(Request $request, AppointmentType $appointment_type)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'price'     => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $appointment_type->update([
            'title'     => $request->title,
            'price'     => $request->price,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.appointment-types.index')
            ->with('success', 'Appointment Type updated successfully!');
    }

    public function destroy(AppointmentType $appointment_type)
    {
        $appointment_type->delete();

        return redirect()->route('admin.appointment-types.index')
            ->with('success', 'Appointment Type deleted successfully!');
    }
}