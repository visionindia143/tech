<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnimalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $animalTypes = [
            [
                'name' => 'Dogs',
                'icon_class' => 'fas fa-dog',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cats',
                'icon_class' => 'fas fa-cat',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Birds',
                'icon_class' => 'fas fa-dove',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fish',
                'icon_class' => 'fas fa-fish',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Small Pets',
                'icon_class' => 'fas fa-paw',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Large Animals',
                'icon_class' => 'fas fa-horse',
                'sort_order' => 6,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\AnimalType::insert($animalTypes);
    }
}
