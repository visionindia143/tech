<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PetTypeForNutrition;
use Illuminate\Http\Request;

class PetTypeForNutritionController extends Controller
{
    public function index()
    {
        $petTypes = PetTypeForNutrition::orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('admin.pet-types-for-nutrition.index', compact('petTypes'));
    }

    public function create()
    {
        return view('admin.pet-types-for-nutrition.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100|unique:pet_type_for_nutrition,name',
            'icon'       => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        PetTypeForNutrition::create([
            'name'       => $request->name,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.pet-types-for-nutrition.index')->with('success', 'Pet type created successfully!');
    }

  public function edit(PetTypeForNutrition $pet_types_for_nutrition)
{
    // Yahan $petType ki jagah $pet_types_for_nutrition use karein
    return view('admin.pet-types-for-nutrition.edit', ['petType' => $pet_types_for_nutrition]);
}
    public function update(Request $request, PetTypeForNutrition $pet_types_for_nutrition)
    {
        $request->validate([
       'name' => 'required|string|max:100|unique:pet_type_for_nutrition,name,' . $pet_types_for_nutrition->id,
            'icon'       => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $pet_types_for_nutrition->update([
        'name'       => $request->name,
        'icon'       => $request->icon,
        'sort_order' => $request->sort_order ?? 0,
        'is_active'  => $request->boolean('is_active', true),
    ]);

        return redirect()->route('admin.pet-types-for-nutrition.index')->with('success', 'Pet type updated successfully!');
    }

  public function destroy(PetTypeForNutrition $pet_types_for_nutrition)
{
    $pet_types_for_nutrition->delete();
    return redirect()->route('admin.pet-types-for-nutrition.index')->with('success', 'Deleted!');
}
}
