<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PetTypeForNutrition;

class NutritionController extends Controller
{
    public function index()
    {
        $petTypes = PetTypeForNutrition::active()
            ->with(['nutritionProducts' => function ($query) {
                $query->active();
            }])
            ->get();

        $productsByPet = $petTypes->mapWithKeys(function ($petType) {
            return [
                (string) $petType->id => $petType->nutritionProducts->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'description' => $product->description,
                        'show_price' => (bool) $product->show_price,
                        'price' => $product->price,
                        'primary_image_url' => $product->primary_image_url,
                        'image_urls' => $product->image_urls,
                    ];
                })->values(),
            ];
        });

        return view('frontend.pet-nutrition', compact('petTypes', 'productsByPet'));
    }
}
