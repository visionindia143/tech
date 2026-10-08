<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Priya Sharma',
                'designation' => 'Dog Owner',
                'message' => 'Outstanding service! The team at PawCare took excellent care of my Labrador during his dental procedure. They were professional, caring, and kept me updated throughout. My dog Max is back to his playful self!',
                'rating' => 5,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rajesh Kumar',
                'designation' => 'Cat Parent',
                'message' => 'I adopted my beautiful Persian cat Luna from PawCare, and the adoption process was so smooth. They provided all medical records and even helped with the transition to our home. Highly recommended!',
                'rating' => 5,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anjali Patel',
                'designation' => 'Bird Enthusiast',
                'message' => 'The avian specialists at PawCare are amazing! They diagnosed and treated my parrot\'s respiratory infection quickly. The grooming services are also top-notch. My birds have never looked better.',
                'rating' => 5,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vikram Singh',
                'designation' => 'Pet Owner',
                'message' => 'Emergency services saved my dog\'s life! When Bruno swallowed something dangerous, PawCare\'s 24/7 emergency team acted quickly. Their expertise and compassion are unmatched.',
                'rating' => 5,
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meera Joshi',
                'designation' => 'Small Animal Lover',
                'message' => 'I bring my rabbits and guinea pigs to PawCare regularly. The staff\'s knowledge about small animals is impressive, and they handle my nervous pets with such care and patience.',
                'rating' => 5,
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Amit Agarwal',
                'designation' => 'First-time Pet Owner',
                'message' => 'As a new pet owner, I was nervous about vaccinations, but the team at PawCare made me feel comfortable. They explained everything clearly and took great care of my puppy. Thank you!',
                'rating' => 5,
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\Testimonial::insert($testimonials);
    }
}
