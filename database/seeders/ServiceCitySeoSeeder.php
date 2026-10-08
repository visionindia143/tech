<?php

namespace Database\Seeders;

use App\Models\ServiceCitySeo;
use Illuminate\Database\Seeder;

class ServiceCitySeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example data for service-city combinations
        // You can modify this based on your actual services and cities

        $serviceCitySeoData = [
            // Pet Grooming examples
            [
                'service_id' => 1, // Assuming Pet Grooming is ID 1
                'city_id' => 1,    // Assuming first city
                'seo_title' => 'Professional Pet Grooming in Mohali - PawZone',
                'meta_description' => 'Get professional pet grooming services in Mohali at PawZone. Expert groomers, hygienic environment, and happy pets guaranteed!',
                'meta_keywords' => 'pet grooming in Mohali, dog grooming Mohali, cat grooming, pet spa',
                'custom_content' => '<h2>Why Choose PawZone for Pet Grooming in Mohali?</h2><p>We provide premium pet grooming services with trained professionals...</p>',
                'faq_content' => json_encode([
                    [
                        'question' => 'How often should I groom my pet?',
                        'answer' => 'It depends on the breed and coat type. Generally, monthly grooming is recommended.'
                    ],
                    [
                        'question' => 'Is grooming safe for senior pets?',
                        'answer' => 'Yes, we have gentle techniques suitable for senior pets. Consult our team for customized care.'
                    ]
                ]),
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'service_id' => 1,
                'city_id' => 2,    // Another city
                'seo_title' => 'Pet Grooming Services in Chandigarh - PawZone',
                'meta_description' => 'Professional dog and cat grooming in Chandigarh. Trusted by pet parents for quality care and hygiene.',
                'meta_keywords' => 'pet grooming Chandigarh, dog spa, pet care services',
                'is_active' => true,
                'priority' => 1,
            ],
            // Pet Boarding examples
            [
                'service_id' => 2, // Assuming Pet Boarding is ID 2
                'city_id' => 1,
                'seo_title' => 'Pet Boarding in Mohali - Safe & Comfortable Stay - PawZone',
                'meta_description' => 'Professional pet boarding facilities in Mohali with 24/7 care, comfortable accommodation, and experienced staff.',
                'meta_keywords' => 'pet boarding Mohali, dog boarding, cat boarding, pet hotel',
                'is_active' => true,
                'priority' => 1,
            ],
        ];

        foreach ($serviceCitySeoData as $data) {
            // Check if combination already exists
            $exists = ServiceCitySeo::where('service_id', $data['service_id'])
                ->where('city_id', $data['city_id'])
                ->exists();

            if (!$exists) {
                ServiceCitySeo::create($data);
            }
        }

        $this->command->info('✅ Service-City SEO data seeded successfully!');
    }
}
