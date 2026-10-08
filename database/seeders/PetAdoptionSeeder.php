<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetAdoptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pets = [
            [
                'name' => 'Buddy',
                'slug' => 'buddy',
                'pet_type' => 'dog',
                'breed' => 'Golden Retriever',
                'age' => '2 years',
                'gender' => 'male',
                'size' => 'large',
                'color' => 'Golden',
                'description' => 'Buddy is a friendly and energetic Golden Retriever who loves playing fetch and going for walks. He\'s great with kids and other pets.',
                'short_description' => 'Friendly Golden Retriever looking for his forever home',
                'adoption_fee' => 2500.00,
                'is_vaccinated' => true,
                'is_neutered' => true,
                'status' => 'available',
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Luna',
                'slug' => 'luna',
                'pet_type' => 'cat',
                'breed' => 'Persian',
                'age' => '1.5 years',
                'gender' => 'female',
                'size' => 'medium',
                'color' => 'White',
                'description' => 'Luna is a beautiful Persian cat with long, silky fur. She\'s calm, affectionate, and loves being groomed. Perfect for a quiet household.',
                'short_description' => 'Beautiful Persian cat seeking loving home',
                'adoption_fee' => 1800.00,
                'is_vaccinated' => true,
                'is_neutered' => true,
                'status' => 'available',
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Max',
                'slug' => 'max',
                'pet_type' => 'dog',
                'breed' => 'Labrador',
                'age' => '3 years',
                'gender' => 'male',
                'size' => 'large',
                'color' => 'Chocolate',
                'description' => 'Max is a loyal and playful Labrador who loves water and outdoor activities. He\'s been trained and is great with families.',
                'short_description' => 'Energetic Labrador ready for adventure',
                'adoption_fee' => 2200.00,
                'is_vaccinated' => true,
                'is_neutered' => true,
                'status' => 'available',
                'is_active' => true,
                'is_featured' => false,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\PetAdoption::insert($pets);
    }
}
