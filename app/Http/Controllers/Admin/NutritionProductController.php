<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NutritionProduct;
use App\Models\PetTypeForNutrition;
use Illuminate\Http\Request;

class NutritionProductController extends Controller
{
    public function index()
    {
        $products = NutritionProduct::with('petType')->orderBy('sort_order')->orderBy('name')->paginate(15);

        return view('admin.nutrition-products.index', compact('products'));
    }

    public function create()
    {
        $petTypes = PetTypeForNutrition::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.nutrition-products.create', compact('petTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pet_type_id' => 'required|exists:pet_type_for_nutrition,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:3000',
            'price' => 'nullable|numeric|min:0|max:999999.99',
            'show_price' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $data['show_price'] = $request->boolean('show_price');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['images'] = $this->storeImages($request);

        NutritionProduct::create($data);

        return redirect()->route('admin.nutrition-products.index')->with('success', 'Nutrition product added successfully.');
    }

    public function edit(NutritionProduct $nutritionProduct)
    {
        $petTypes = PetTypeForNutrition::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.nutrition-products.edit', [
            'product' => $nutritionProduct,
            'petTypes' => $petTypes,
        ]);
    }

    public function update(Request $request, NutritionProduct $nutritionProduct)
    {
        $data = $request->validate([
            'pet_type_id' => 'required|exists:pet_type_for_nutrition,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:3000',
            'price' => 'nullable|numeric|min:0|max:999999.99',
            'show_price' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'remove_images' => 'nullable|array',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $data['show_price'] = $request->boolean('show_price');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $existingImages = $nutritionProduct->images ?? [];
        $uploadedImages = $this->storeImages($request);

        if ($request->has('remove_images')) {
            $toRemove = array_values(array_filter((array) $request->input('remove_images', [])));
            $existingImages = array_values(array_diff($existingImages, $toRemove));

            foreach ($toRemove as $image) {
                $this->deleteImageFile($image);
            }
        }

        $data['images'] = array_values(array_merge($existingImages, $uploadedImages));

        $nutritionProduct->update($data);

        return redirect()->route('admin.nutrition-products.index')->with('success', 'Nutrition product updated successfully.');
    }

    public function destroy(NutritionProduct $nutritionProduct)
    {
        foreach ($nutritionProduct->images ?? [] as $image) {
            $this->deleteImageFile($image);
        }

        $nutritionProduct->delete();

        return redirect()->route('admin.nutrition-products.index')->with('success', 'Nutrition product deleted successfully.');
    }

    private function storeImages(Request $request): array
    {
        $uploaded = [];

        if ($request->hasFile('images')) {
            $directory = public_path('uploads/nutrition-products');

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($directory, $filename);
                $uploaded[] = $filename;
            }
        }

        return $uploaded;
    }

    private function deleteImageFile(string $filename): void
    {
        $path = public_path('uploads/nutrition-products/' . $filename);

        if (file_exists($path)) {
            @unlink($path);
        }
    }
}
