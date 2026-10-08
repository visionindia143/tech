<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Setting, Blog, Slider, Gallery, Service, City, VaccinationType, Page};
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Settings
        $settings = [
            'site_name' => 'PawCare',
            'site_tagline' => 'Your trusted partner for premium pet care, vaccinations, grooming and everything your furry friend needs.',
            'site_email' => 'hello@pawcare.com',
            'site_phone' => '+91 9876543210',
            'site_address' => '123 Pet Street, Sector 12, Gurugram, Haryana',
            'default_meta_title' => 'PawCare - Premium Pet Shop & Pet Care Services',
            'default_meta_description' => 'PawCare offers professional pet care services including vaccinations, grooming, training and boarding across major Indian cities.',
            'default_meta_keywords' => 'pet care, pet shop, pet vaccination, dog grooming, cat care, pet boarding',
            'facebook_url' => 'https://facebook.com/pawcare',
            'instagram_url' => 'https://instagram.com/pawcare',
            'twitter_url' => 'https://twitter.com/pawcare',
            'whatsapp_number' => '+919876543210',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // Sliders
        $sliders = [
            ['title' => 'Your Pet Deserves the Best Care', 'subtitle' => 'Professional pet care services with love and compassion. Book vaccinations, grooming, and more.', 'button_text' => 'Book Now', 'button_link' => '/vaccination-booking', 'order' => 1],
            ['title' => 'Expert Veterinary Services', 'subtitle' => 'Our certified veterinarians provide top-quality healthcare for your beloved companions.', 'button_text' => 'Our Services', 'button_link' => '/#services', 'order' => 2],
            ['title' => 'Find Your Perfect Companion', 'subtitle' => 'Browse our gallery of adorable pets looking for loving homes.', 'button_text' => 'View Gallery', 'button_link' => '/#gallery', 'order' => 3],
        ];

        foreach ($sliders as $s) {
            Slider::create(array_merge($s, ['image' => '', 'is_active' => true]));
        }

        // Services
        $services = [
            ['title' => 'Pet Vaccination', 'icon' => 'fas fa-syringe', 'short_description' => 'Complete vaccination schedules for dogs, cats, and other pets to keep them protected.', 'price' => 500],
            ['title' => 'Pet Grooming', 'icon' => 'fas fa-cut', 'short_description' => 'Professional grooming services including bath, haircut, nail trimming, and ear cleaning.', 'price' => 800],
            ['title' => 'Pet Training', 'icon' => 'fas fa-graduation-cap', 'short_description' => 'Behavioral training and obedience classes by certified pet trainers.', 'price' => 1500],
            ['title' => 'Pet Boarding', 'icon' => 'fas fa-home', 'short_description' => 'Safe and comfortable boarding facilities with 24/7 care and monitoring.', 'price' => 1000],
            ['title' => 'Health Checkup', 'icon' => 'fas fa-heartbeat', 'short_description' => 'Comprehensive health examinations and diagnostic services for pets.', 'price' => 700],
            ['title' => 'Pet Nutrition', 'icon' => 'fas fa-bone', 'short_description' => 'Expert dietary consultation and premium nutrition plans for your pets.', 'price' => 300],
        ];

        foreach ($services as $i => $s) {
            Service::create(array_merge($s, [
                'slug' => Str::slug($s['title']),
                'order' => $i + 1,
                'is_active' => true,
            ]));
        }

        // Cities
        $cities = ['Delhi', 'Mumbai', 'Bangalore', 'Hyderabad', 'Chennai', 'Pune'];
        foreach ($cities as $i => $city) {
            City::create([
                'name' => $city,
                'slug' => Str::slug($city),
                'description' => "Premium pet care services available in {$city}",
                'order' => $i + 1,
                'is_active' => true,
            ]);
        }

        // Vaccination Types
        $vaccinations = [
            ['name' => 'Rabies Vaccine', 'description' => 'Essential protection against rabies for dogs and cats', 'price' => 500],
            ['name' => 'DHPP (Distemper)', 'description' => 'Protects against Distemper, Hepatitis, Parvovirus, Parainfluenza', 'price' => 800],
            ['name' => 'Bordetella', 'description' => 'Protection against kennel cough', 'price' => 600],
            ['name' => 'Feline FVRCP', 'description' => 'Core vaccine for cats covering viral rhinotracheitis, calicivirus, panleukopenia', 'price' => 700],
            ['name' => 'Leptospirosis', 'description' => 'Bacterial infection protection for dogs', 'price' => 550],
        ];

        foreach ($vaccinations as $v) {
            VaccinationType::create(array_merge($v, ['is_active' => true]));
        }

        // Blogs
        $blogs = [
            [
                'title' => '10 Essential Tips for First-Time Dog Owners',
                'content' => '<h2>Welcome to Dog Parenthood!</h2><p>Getting your first dog is an exciting experience. Here are 10 essential tips to help you and your new furry friend get off to a great start.</p><h3>1. Choose the Right Breed</h3><p>Research different breeds to find one that matches your lifestyle, living space, and activity level. Consider factors like size, energy level, grooming needs, and temperament.</p><h3>2. Prepare Your Home</h3><p>Dog-proof your home by removing hazardous items, securing trash cans, and creating a comfortable space for your new pet. Set up a cozy bed, food and water bowls, and plenty of toys.</p><h3>3. Schedule a Vet Visit</h3><p>Take your new dog to the veterinarian within the first week. Get a thorough health check, discuss vaccination schedules, and set up a preventive care plan.</p><h3>4. Establish a Routine</h3><p>Dogs thrive on routine. Set consistent times for feeding, walks, play, and sleep. This helps your dog feel secure and makes training easier.</p><h3>5. Start Training Early</h3><p>Begin basic obedience training as soon as possible. Focus on commands like sit, stay, come, and leave it. Use positive reinforcement with treats and praise.</p><p>Remember, patience is key. Your new dog needs time to adjust to their new environment, and building a strong bond takes time and consistent effort.</p>',
                'meta_title' => '10 Essential Tips for First-Time Dog Owners | PawCare',
                'meta_description' => 'New to dog ownership? Learn the 10 most important tips every first-time dog owner should know for a happy, healthy pet.',
                'meta_keywords' => 'first time dog owner, dog care tips, new puppy advice',
                'status' => 'publish',
            ],
            [
                'title' => 'Understanding Your Cat\'s Body Language',
                'content' => '<h2>Decode Your Feline Friend</h2><p>Cats communicate through their body language. Understanding these signals can help you build a stronger bond with your feline companion.</p><h3>Tail Positions</h3><p>A cat\'s tail is one of the most expressive parts of their body. A high, upright tail indicates confidence and happiness, while a puffed-up tail signals fear or aggression. A slowly swaying tail often means your cat is focused or contemplating something.</p><h3>Ear Movements</h3><p>Forward-facing ears show interest and alertness. Ears that are flattened against the head indicate fear or aggression. Ears that rotate like satellite dishes are taking in sounds from different directions.</p><h3>Eye Signals</h3><p>Slow blinking is a sign of trust and affection — it\'s often called a "cat kiss." Dilated pupils can indicate excitement or fear, depending on the context. Direct staring is considered a challenge in cat language.</p><p>Learning to read your cat\'s body language takes time, but it\'s one of the most rewarding aspects of cat ownership.</p>',
                'meta_title' => 'Understanding Your Cat\'s Body Language | PawCare Blog',
                'meta_description' => 'Learn to decode your cat\'s body language. Understand what tail positions, ear movements, and eye signals mean.',
                'meta_keywords' => 'cat body language, cat behavior, understanding cats',
                'status' => 'publish',
            ],
            [
                'title' => 'The Complete Guide to Pet Vaccination Schedules',
                'content' => '<h2>Why Vaccinations Matter</h2><p>Vaccinations are one of the most important things you can do to protect your pet\'s health. They help prevent serious and potentially fatal diseases.</p><h3>Puppy Vaccination Schedule</h3><p>Puppies should start their vaccination series at 6-8 weeks of age. Core vaccines include DHPP (Distemper, Hepatitis, Parvovirus, Parainfluenza) and Rabies. Boosters are typically given at 10-12 weeks and 14-16 weeks.</p><h3>Kitten Vaccination Schedule</h3><p>Kittens should receive their first FVRCP vaccine at 6-8 weeks. The series continues every 3-4 weeks until 16 weeks of age. Rabies vaccine is typically given at 12-16 weeks.</p><h3>Adult Pet Vaccinations</h3><p>Adult pets need regular booster shots to maintain their immunity. Core vaccines are typically boosted every 1-3 years, depending on the vaccine and your veterinarian\'s recommendation.</p><p>Always consult with your veterinarian to create a personalized vaccination schedule for your pet.</p>',
                'meta_title' => 'Complete Pet Vaccination Schedule Guide | PawCare',
                'meta_description' => 'Everything you need to know about pet vaccination schedules for puppies, kittens, and adult pets.',
                'meta_keywords' => 'pet vaccination schedule, puppy vaccines, kitten vaccines',
                'status' => 'publish',
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create(array_merge($blog, [
                'slug' => Str::slug($blog['title']),
            ]));
        }

        // Gallery
        $pets = [
            ['title' => 'Max', 'pet_type' => 'Dog', 'breed' => 'Golden Retriever'],
            ['title' => 'Luna', 'pet_type' => 'Cat', 'breed' => 'Persian'],
            ['title' => 'Buddy', 'pet_type' => 'Dog', 'breed' => 'Labrador'],
            ['title' => 'Whiskers', 'pet_type' => 'Cat', 'breed' => 'Maine Coon'],
            ['title' => 'Rocky', 'pet_type' => 'Dog', 'breed' => 'German Shepherd'],
            ['title' => 'Coco', 'pet_type' => 'Dog', 'breed' => 'Poodle'],
            ['title' => 'Milo', 'pet_type' => 'Cat', 'breed' => 'Siamese'],
            ['title' => 'Daisy', 'pet_type' => 'Dog', 'breed' => 'Beagle'],
        ];

        foreach ($pets as $i => $pet) {
            Gallery::create(array_merge($pet, [
                'image' => '',
                'order' => $i + 1,
                'is_active' => true,
            ]));
        }

        // Pages
        Page::create([
            'title' => 'About',
            'slug' => 'about',
            'content' => '<h2>Welcome to PawCare</h2><p>We are a passionate team of pet lovers and veterinary professionals dedicated to providing the highest quality care for your beloved companions.</p><p>Founded in 2020, PawCare has grown to serve thousands of pet parents across major Indian cities. Our mission is simple: to make premium pet care accessible, affordable, and convenient for every pet owner.</p><h3>Our Values</h3><p><strong>Compassion:</strong> We treat every pet as if they were our own.</p><p><strong>Excellence:</strong> We maintain the highest standards in veterinary care and service.</p><p><strong>Innovation:</strong> We leverage technology to make pet care easier and more accessible.</p><p><strong>Community:</strong> We believe in building a strong community of responsible pet owners.</p>',
            'meta_title' => 'About PawCare - Our Story & Mission',
            'meta_description' => 'Learn about PawCare\'s mission to provide premium pet care services across India.',
            'is_active' => true,
        ]);

        Page::create([
            'title' => 'Contact',
            'slug' => 'contact',
            'content' => '<p>We would love to hear from you! Whether you have questions about our services, need advice about your pet, or want to partner with us, don\'t hesitate to reach out.</p>',
            'meta_title' => 'Contact PawCare - Get in Touch',
            'meta_description' => 'Contact PawCare for pet care inquiries, vaccination bookings, and partnership opportunities.',
            'is_active' => true,
        ]);

        // Run additional seeders
        $this->call([
            FaqSeeder::class,
            TestimonialSeeder::class,
            AnimalTypeSeeder::class,
            PetAdoptionSeeder::class,
        ]);
    }
}
