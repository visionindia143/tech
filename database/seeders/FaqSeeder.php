<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What types of pets do you care for?',
                'answer' => 'We provide comprehensive care for dogs, cats, birds, and small mammals including rabbits, guinea pigs, and hamsters. Our experienced team is trained to handle various breeds and temperaments.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Do you offer emergency veterinary services?',
                'answer' => 'Yes, we provide 24/7 emergency veterinary services for critical situations. If your pet needs immediate medical attention, call our emergency hotline at +91-9876543210.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What vaccinations do you recommend for my pet?',
                'answer' => 'Vaccination schedules vary by pet type, age, and lifestyle. For dogs, we recommend core vaccines including DHPP, Rabies, and Bordetella. Cats need FVRCP and Rabies. We\'ll create a customized vaccination plan during your pet\'s wellness check.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'How much does pet adoption cost?',
                'answer' => 'Adoption fees vary depending on the pet\'s age, breed, and medical history. Our adoption fees typically range from ₹500 to ₹5,000 and include spaying/neutering, initial vaccinations, and microchipping. Many pets are also available at reduced rates.',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What grooming services do you offer?',
                'answer' => 'We offer comprehensive grooming services including bathing, nail trimming, ear cleaning, teeth brushing, and breed-specific haircuts. Our professional groomers use premium, pet-safe products and ensure your pet\'s comfort throughout the process.',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'How do I book an appointment?',
                'answer' => 'You can book appointments through our website, mobile app, or by calling our reception. For vaccinations and medical consultations, we recommend booking at least 2-3 days in advance. Walk-ins are welcome for urgent cases.',
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\Faq::insert($faqs);
    }
}
