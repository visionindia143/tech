<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnimalType;
use Illuminate\Http\Request;

class AnimalTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $animalTypes = AnimalType::ordered()->get();
        return view('admin.animal-types.index', compact('animalTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.animal-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon_class' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        AnimalType::create($request->all());

        return redirect()->route('admin.animal-types.index')->with('success', 'Animal type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnimalType $animalType)
    {
        return view('admin.animal-types.edit', compact('animalType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnimalType $animalType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon_class' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $animalType->update($request->all());

        return redirect()->route('admin.animal-types.index')->with('success', 'Animal type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnimalType $animalType)
    {
        $animalType->delete();
        return redirect()->route('admin.animal-types.index')->with('success', 'Animal type deleted successfully.');
    }
}
