<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaccinationType;
use Illuminate\Http\Request;

class VaccinationTypeController extends Controller
{
    public function index()
    {
        $types = VaccinationType::paginate(15);
        return view('admin.vaccination-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.vaccination-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
        ]);

        VaccinationType::create($request->all());
        return redirect()->route('admin.vaccination-types.index')->with('success', 'Vaccination type created successfully!');
    }

    public function edit(VaccinationType $vaccinationType)
    {
        return view('admin.vaccination-types.edit', compact('vaccinationType'));
    }

    public function update(Request $request, VaccinationType $vaccinationType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
        ]);

        $vaccinationType->update($request->all());
        return redirect()->route('admin.vaccination-types.index')->with('success', 'Vaccination type updated successfully!');
    }

    public function destroy(VaccinationType $vaccinationType)
    {
        $vaccinationType->delete();
        return redirect()->route('admin.vaccination-types.index')->with('success', 'Vaccination type deleted successfully!');
    }
}
