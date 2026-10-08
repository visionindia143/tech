<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PetType;
use Illuminate\Http\Request;

class PetTypeController extends Controller
{
    public function index()
    {
        $petTypes = PetType::orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('admin.pet-types.index', compact('petTypes'));
    }

    public function create()
    {
        return view('admin.pet-types.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100|unique:pet_types,name',
            'icon'       => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        PetType::create([
            'name'       => $request->name,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.pet-types.index')->with('success', 'Pet type created successfully!');
    }

    public function edit(PetType $petType)
    {
        return view('admin.pet-types.edit', compact('petType'));
    }

    public function update(Request $request, PetType $petType)
    {
        $request->validate([
            'name'       => 'required|string|max:100|unique:pet_types,name,' . $petType->id,
            'icon'       => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $petType->update([
            'name'       => $request->name,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.pet-types.index')->with('success', 'Pet type updated successfully!');
    }

    public function destroy(PetType $petType)
    {
        $petType->delete();
        return redirect()->route('admin.pet-types.index')->with('success', 'Pet type deleted.');
    }
}
