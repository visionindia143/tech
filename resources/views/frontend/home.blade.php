@extends('layouts.frontend')

@section('styles')
    <style>
        /* SECTION */
        .st-card-section {
            padding: 60px 20px;
            background: #f9fafc;
            font-family: 'Segoe UI', sans-serif;
        }


        /* GRID */
        {{-- .st-card-section .st-container {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        } --}}
        
         .st-card-section .st-container {
            max-width: 1200px;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        .st-card-section .st-card {
            width: calc(25% - 20px);
        }

        /* CARD (NOW LINK) */
        .st-card-section .st-card {
            display: block;
            text-decoration: none;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.4s ease;
            text-align: center;
            padding-bottom: 20px;
            cursor: pointer;
        }

        .st-card-section .st-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        /* IMAGE */
        .st-card-section .st-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        /* TEXT */
        .st-card-section .st-card h3 {
            margin: 15px 0 10px;
            font-size: 20px;
            color: #222;
        }

        .st-card-section .st-card p {
            font-size: 14px;
            color: #666;
            padding: 0 15px;
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .st-card-section .st-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }


        @media (max-width: 600px) {

            .st-card-section .st-container {
                grid-template-columns: 1fr;
                /* 🔥 1 card per row */
            }

            .st-card-section .st-card {
                width: 100%;
            }

            .st-card-section .st-card img {
                height: 220px;
                /* optional – better look */
            }
        }

        /* ── Section Particles ───────────────────────────────────── */
        .sec-has-particles {
            position: relative;
            overflow: hidden;
        }

        .sec-particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        /* Content inside particle sections must be above particles */
        .sec-has-particles>.container {
            position: relative;
            z-index: 1;
        }

        .sp {
            position: absolute;
        }

        /* Paw icon particle */
        .sp-paw {
            font-size: 1.1rem;
            color: var(--clr-primary);
            opacity: 0.07;
            animation: spDrift ease-in-out infinite;
        }

        /* Dot particle */
        .sp-dot {
            border-radius: 50%;
            background: var(--clr-primary);
            opacity: 0.07;
            animation: spDrift ease-in-out infinite;
        }

        /* Star particle */
        .sp-star {
            font-size: 0.9rem;
            color: #F59E0B;
            opacity: 0.12;
            animation: spDrift ease-in-out infinite;
        }

        /* Accent dot */
        .sp-dot-accent {
            border-radius: 50%;
            background: var(--clr-accent);
            opacity: 0.08;
            animation: spDrift ease-in-out infinite;
        }

        /* Heart particle */
        .sp-heart {
            font-size: 0.9rem;
            color: #EF4444;
            opacity: 0.08;
            animation: spDrift ease-in-out infinite;
        }

        @keyframes spDrift {
            0% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }

            25% {
                transform: translateY(-14px) rotate(6deg) scale(1.06);
            }

            50% {
                transform: translateY(-22px) rotate(-4deg) scale(0.95);
            }

            75% {
                transform: translateY(-10px) rotate(8deg) scale(1.03);
            }

            100% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }
        }



        .hero-slide-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            /* desktop image */
            background-image: var(--bg-pc);
        }

        /* mobile image switch */
        @media (max-width: 768px) {
            .hero-slide-bg {
                background-image: var(--bg-mb);
            }
        }

        /* ─────────────────────────────────────────────────────────── */
    </style>
@endsection

@section('content')

    <div style="position: relative; overflow: hidden;">
        {{-- FLOATING PET PARTICLES --}}
        <div class="pet-particles" id="petParticles"></div>

        {{-- HERO SLIDER --}}
        @if ($sliders->count())
            <section class="hero">
                <div class="hero-slider" id="heroSlider">
                    @foreach ($sliders as $slider)
                        <div class="hero-slide {{ $slider->mobile_image ? 'has-mobile-bg' : '' }}">
                            <div class="hero-slide-bg"
                                style="--bg-pc: url('{{ $slider->image_url }}'); 
                                --bg-mb: url('{{ $slider->mobile_image ? $slider->mobile_image_url : $slider->image_url }}');">
                            </div>
                            <div class="hero-slide-content">
                                <h1>{{ $slider->title }}</h1>
                                @if ($slider->subtitle)
                                    <p>{{ $slider->subtitle }}</p>
                                @endif
                                @if ($slider->button_text)
                                    <a href="{{ $slider->button_link ?? '#' }}" class="btn btn-primary">
                                        {{ $slider->button_text }} <i class="fas fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($sliders->count() > 1)
                    <div class="hero-nav">
                        @foreach ($sliders as $i => $slider)
                            <button class="hero-dot {{ $i === 0 ? 'active' : '' }}"
                                onclick="goToSlide({{ $i }})"></button>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif

        <div class="running-text">
            <div class="running-track">
                <span>🐾 Welcome to Pretty Pet 🐾</span>
                <span>❤️ Love & Care for Your Beloved Pets ❤️</span>
                <span>🐶 Healthy Pets, Happy Homes 🐱</span>
                <span>🩺 Book Vaccination & Pet Care Services Today</span>
                <span>🐾 Trusted Care for Your Furry Friends 🐾</span>
                <span>🛒 Shop Premium Pet Food & Accessories</span>
                <span>💖 Because Your Pet Deserves the Best</span>

                <!-- duplicate for smooth loop -->
                <span>🐾 Welcome to Pretty Pet 🐾</span>
                <span>❤️ Love & Care for Your Beloved Pets ❤️</span>
                <span>🐶 Healthy Pets, Happy Homes 🐱</span>
                <span>🩺 Book Vaccination & Pet Care Services Today</span>
                <span>🐾 Trusted Care for Your Furry Friends 🐾</span>
                <span>🛒 Shop Premium Pet Food & Accessories</span>
                <span>💖 Because Your Pet Deserves the Best</span>
            </div>
        </div>


        {{-- <div class="custom-carousel-wrapper" style="margin-top:20px;">

           
            <div class="ccw-bg" id="ccwBg">
                <div class="ccw-orb" style="width:300px;height:300px;top:-80px;left:-60px;animation-duration:5s"></div>
                <div class="ccw-orb"
                    style="width:220px;height:220px;bottom:-60px;right:-40px;animation-duration:7s;animation-delay:1s">
                </div>
                <div class="ccw-orb"
                    style="width:150px;height:150px;top:40%;left:45%;animation-duration:6s;animation-delay:2s"></div>
                <span class="ccw-paw" style="top:10%;left:5%;animation-duration:4s">🐾</span>
                <span class="ccw-paw" style="top:65%;left:88%;animation-duration:5s;animation-delay:1s">🐾</span>
                <span class="ccw-paw"
                    style="top:20%;left:75%;animation-duration:3.5s;animation-delay:.5s;font-size:1.2rem">🐾</span>
                <span class="ccw-paw"
                    style="top:75%;left:15%;animation-duration:6s;animation-delay:2s;font-size:1.4rem">🐾</span>
            </div>

            <h1>Animals We <span style="color:var(--clr-primary)">Treat</span></h1>

            <div class="carousel">
                <div class="carousel-content">
                    @forelse($animalTypes as $animalType)
                        <div class="card">
                            <div class="icon-wrap">
                                <i class="{{ $animalType->icon_class ?? 'fas fa-paw' }}"></i>
                            </div>
                            <p>{{ $animalType->name }}</p>
                        </div>
                    @empty
                        <div class="card">
                            <div class="icon-wrap"><i class="fas fa-paw"></i></div>
                            <p>Coming Soon</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div> --}}



        <section class="st-card-section">
            <div class="section-header fade-up">
                <span class="section-badge"><i class="fas fa-heart"></i> What We Offer</span>
                <h2>Book Pet Care Services</h2>
                <p>Quickly schedule appointments, tests, vaccinations, or consult our vets online</p>
            </div>
            <div class="st-container">


                <a href="{{ route('appointment') }}" class="st-card">
                    <img src="https://images.unsplash.com/photo-1535930749574-1399327ce78f?q=80&w=736&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Vet Appointment">
                    <h3>Book Appointment</h3>
                    <p>Schedule a visit with our experienced veterinarians.</p>
                </a>

                <a href="{{ route('diagnostic.booking') }}" class="st-card">
                    <img src="https://plus.unsplash.com/premium_photo-1661942274165-00cc8d55a93f?q=80&w=1169&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Pet Diagnostic">
                    <h3>Diagnostic / Test</h3>
                    <p>Book lab tests and diagnostics for your pet.</p>
                </a>

                <a href="{{ route('vaccination') }}" class="st-card">
                    <img src="https://plus.unsplash.com/premium_photo-1702599116608-639ae9da1127?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Vaccination">
                    <h3>Vaccination & Deworming</h3>
                    <p>Keep your pet safe with timely vaccinations.</p>
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}?text={{ urlencode('Hey Pretty Pet, I want pet boarding for my pet.') }}"
                    target="_blank" class="st-card">

                    <img src="https://images.unsplash.com/photo-1552053831-71594a27632d?q=80&w=662&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Teleconsult">
                    <h3>Pet Boarding</h3>
                    <p>Provide a safe and comfortable environment for your pet while you're away.</p>

                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}?text={{ urlencode('Hey Pretty Pet, I want teleconsult for my pet.') }}"
                    target="_blank" class="st-card">

                    <img src="https://images.unsplash.com/photo-1666886573301-b5d526cfd518?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Teleconsult">
                    <h3>Teleconsult</h3>
                    <p>Consult vets online from the comfort of your home.</p>

                </a>
                
            </div>
        </section>
        {{-- PET GALLERY --}}
        @if ($galleries->count())
            <section class="section sec-has-particles">

                {{-- Gallery Particles --}}
                <div class="sec-particles" aria-hidden="true">
                    <i class="sp sp-paw fas fa-paw"
                        style="top:8%;left:3%;font-size:1.8rem;animation-duration:6s;animation-delay:0s;"></i>
                    <i class="sp sp-paw fas fa-paw"
                        style="top:72%;left:6%;font-size:1.1rem;animation-duration:8s;animation-delay:1s;"></i>
                    <i class="sp sp-paw fas fa-paw"
                        style="top:15%;right:4%;font-size:2rem;animation-duration:7s;animation-delay:0.5s;"></i>
                    <i class="sp sp-paw fas fa-paw"
                        style="top:60%;right:5%;font-size:1.3rem;animation-duration:9s;animation-delay:2s;"></i>
                    <i class="sp sp-paw fas fa-paw"
                        style="top:38%;left:1%;font-size:1rem;animation-duration:5.5s;animation-delay:1.5s;"></i>
                    <i class="sp sp-paw fas fa-paw"
                        style="top:85%;right:10%;font-size:1.5rem;animation-duration:7.5s;animation-delay:3s;"></i>
                    <div class="sp sp-dot"
                        style="width:60px;height:60px;top:5%;left:20%;animation-duration:9s;animation-delay:0.8s;"></div>
                    <div class="sp sp-dot"
                        style="width:40px;height:40px;bottom:8%;left:35%;animation-duration:6.5s;animation-delay:2.5s;">
                    </div>
                    <div class="sp sp-dot"
                        style="width:80px;height:80px;top:30%;right:18%;animation-duration:8s;animation-delay:1.2s;opacity:0.04;">
                    </div>
                    <div class="sp sp-dot-accent"
                        style="width:50px;height:50px;bottom:15%;right:28%;animation-duration:7s;animation-delay:0.3s;">
                    </div>
                    <i class="sp sp-heart fas fa-heart"
                        style="top:50%;left:12%;font-size:1rem;animation-duration:6s;animation-delay:1.8s;"></i>
                    <i class="sp sp-heart fas fa-heart"
                        style="top:20%;right:20%;font-size:0.8rem;animation-duration:8.5s;animation-delay:0.7s;"></i>
                </div>

                <div class="container">
                    <div class="section-header fade-up">
                        <span class="section-badge"><i class="fas fa-camera"></i> Our Friends</span>
                        <h2>Meet Our Adorable Pets</h2>
                        <p>Browse our gallery of lovable companions waiting to brighten your day</p>
                    </div>
                    <div class="gallery-slider-wrap">
                        <div class="gallery-slider" id="gallerySlider">
                            @foreach ($galleries as $gallery)
                                <div class="gallery-slide">
                                    <div class="gallery-card fade-up" data-title="{{ $gallery->title }}"
                                        data-breed="{{ $gallery->breed }}">
                                        <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" loading="lazy">
                                        <div class="gallery-card-overlay">
                                            <h4>{{ $gallery->title }}</h4>
                                            @if ($gallery->breed)
                                                <span>{{ $gallery->breed }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($galleries->count() > 1)
                            <button class="gallery-slider-nav gallery-slider-prev" type="button"
                                onclick="moveGallerySlider(-1)"><i class="fas fa-chevron-left"></i></button>
                            <button class="gallery-slider-nav gallery-slider-next" type="button"
                                onclick="moveGallerySlider(1)"><i class="fas fa-chevron-right"></i></button>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- PET CARE SERVICES --}}
        @if ($services->count())
            <section class="section" style="background: var(--clr-light-gray);">

                <div class="container">
                    <div class="section-header fade-up">
                        <span class="section-badge"><i class="fas fa-heart"></i> What We Offer</span>
                        <h2>Premium Pet Care Services</h2>
                        <p>Everything your pet needs under one roof — from grooming to medical care</p>
                    </div>
                    <div class="services-grid">
                        @foreach ($services as $service)
                            <div class="service-card fade-up" data-url="{{ route('service.show', $service->slug) }}">

                                {{-- 3 animated orbs — yeh add karo --}}
                                <div class="sc-orb sc-orb-1"></div>
                                <div class="sc-orb sc-orb-2"></div>
                                <div class="sc-orb sc-orb-3"></div>

                                <div class="service-icon">
                                    @if ($service->icon)
                                        <i class="{{ $service->icon }}"></i>
                                    @else
                                        <i class="fas fa-paw"></i>
                                    @endif
                                </div>

                                <h3>{{ $service->title }}</h3>
                                <p>{{ $service->short_description }}</p>

                                {{-- @if ($service->price)
                                    <div class="service-price">From ₹{{ number_format($service->price) }}</div>
                                @endif --}}

                                {{-- class change: style inline → learn-link class --}}
                                <a href="{{ route('service.show', $service->slug) }}" class="learn-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>

                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- AVAILABLE CITIES --}}
        @if ($cities->count())
            <section class="section">
                <div class="container">
                    <div class="section-header fade-up">
                        <span class="section-badge"><i class="fas fa-map-marker-alt"></i> Locations</span>
                        <h2>Available in Your City</h2>
                        <p>We're expanding to bring the best pet care closer to you</p>
                    </div>
                    <div class="cities-grid">
                        @foreach ($cities as $city)
                            <a href="{{ route('city.detail', $city->slug) }}" class="city-card fade-up"
                                style="text-decoration:none;color:inherit;display:block;">
                                @if ($city->image)
                                    <img src="{{ $city->image_url }}" alt="{{ $city->name }}" loading="lazy">
                                @else
                                    <div
                                        style="width:100%;height:100%;background:linear-gradient(135deg,var(--clr-primary),var(--clr-accent))">
                                    </div>
                                @endif
                                <div class="city-card-content">
                                    <h3>{{ $city->name }}</h3>
                                    @if ($city->description)
                                        <p>{{ Str::limit($city->description, 60) }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif




        {{-- CTA SECTION --}}
        <section class="section" style="padding-bottom:0;">
            <div class="cta-section fade-up">

                <div class="bubbles-wrap" id="ctaBubbles"></div>

                <div class="cta-badge">
                    <i class="fas fa-star" style="font-size:10px;"></i> Pet Care
                </div>

                <h2>Give Your Pet the Best Care</h2>
                <p>Book a vaccination appointment today and keep your furry friend healthy and happy.</p>

                <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;position:relative;z-index:1;">
                    <a href="{{ route('vaccination') }}" class="btn btn-primary">
                        <i class="fas fa-syringe"></i> Book Vaccination
                    </a>
                    <a href="{{ route('adoption.index') }}" class="btn btn-white">
                        <i class="fas fa-heart"></i> Adopt a Pet
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-white">
                        <i class="fas fa-phone"></i> Contact Us
                    </a>
                </div>

            </div>
        </section>

        {{-- FEATURED BLOGS --}}
        @if ($blogs->count())
            <section class="section sec-has-particles">

                {{-- Blog Particles --}}
                <div class="sec-particles" aria-hidden="true">
                    <i class="sp sp-paw fas fa-paw"
                        style="top:10%;left:2%;font-size:2rem;animation-duration:7s;animation-delay:0s;"></i>
                    <i class="sp sp-paw fas fa-paw"
                        style="top:65%;left:5%;font-size:1.2rem;animation-duration:9s;animation-delay:1.5s;"></i>
                    <i class="sp sp-paw fas fa-paw"
                        style="top:20%;right:3%;font-size:1.5rem;animation-duration:6s;animation-delay:0.8s;"></i>
                    <i class="sp sp-paw fas fa-paw"
                        style="bottom:10%;right:6%;font-size:1.8rem;animation-duration:8s;animation-delay:2s;"></i>
                    <div class="sp sp-dot"
                        style="width:70px;height:70px;top:8%;left:25%;animation-duration:8.5s;animation-delay:0.5s;opacity:0.05;">
                    </div>
                    <div class="sp sp-dot"
                        style="width:45px;height:45px;bottom:12%;left:40%;animation-duration:6s;animation-delay:1s;"></div>
                    <div class="sp sp-dot-accent"
                        style="width:55px;height:55px;top:40%;right:15%;animation-duration:7.5s;animation-delay:0.3s;">
                    </div>
                    <div class="sp sp-dot-accent"
                        style="width:35px;height:35px;bottom:20%;right:30%;animation-duration:9s;animation-delay:2.5s;">
                    </div>
                    <i class="sp sp-star fas fa-star"
                        style="top:12%;right:22%;font-size:1rem;animation-duration:5.5s;animation-delay:1.2s;"></i>
                    <i class="sp sp-star fas fa-star"
                        style="bottom:8%;left:18%;font-size:0.8rem;animation-duration:7s;animation-delay:0.6s;"></i>
                    <i class="sp sp-star fas fa-star"
                        style="top:55%;left:8%;font-size:0.7rem;animation-duration:6.5s;animation-delay:1.8s;"></i>
                    <i class="sp sp-heart fas fa-heart"
                        style="top:30%;right:8%;font-size:0.9rem;animation-duration:8s;animation-delay:0.4s;"></i>
                </div>

                <div class="container">
                    <div class="section-header fade-up">
                        <span class="section-badge"><i class="fas fa-newspaper"></i> Our Blog</span>
                        <h2>Pet Care Tips & Stories</h2>
                        <p>Expert advice and heartwarming stories from the world of pets</p>
                    </div>

                    <div class="blog-slider-wrap" id="blogSliderWrap">
                        <div class="blog-slider" id="blogSlider">
                            @foreach ($blogs as $blog)
                                <div class="blog-slide">
                                    <a href="{{ route('blog.detail', $blog->slug) }}" class="blog-card">
                                        <div class="blog-card-img">
                                            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}">
                                        </div>
                                        <div class="blog-card-body">
                                            <div class="blog-card-date">{{ $blog->created_at->format('M d, Y') }}</div>
                                            <h3>{{ $blog->title }}</h3>
                                            <p>{{ $blog->excerpt }}</p>
                                            <span class="blog-card-link">Read More →</span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <button class="blog-slider-nav blog-slider-prev" onclick="moveBlogSlider(-1)">‹</button>
                        <button class="blog-slider-nav blog-slider-next" onclick="moveBlogSlider(1)">›</button>
                    </div>
                    <div style="text-align:center;margin-top:40px;">
                        <a href="{{ route('blogs') }}" class="btn btn-outline">View All Posts <i
                                class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </section>
        @endif

        {{-- TESTIMONIALS SECTION --}}
        @if ($testimonials->count())
            <section class="section sec-has-particles">
                <div class="sec-particles" aria-hidden="true">
                    <!-- stars -->
                    <span class="sp sp-star"
                        style="font-size:18px;left:6%;top:12%;animation-duration:7s;animation-delay:0s;">&#9733;</span>
                    <span class="sp sp-star"
                        style="font-size:12px;left:82%;top:8%;animation-duration:9s;animation-delay:1.5s;">&#9733;</span>
                    <span class="sp sp-star"
                        style="font-size:22px;left:50%;top:80%;animation-duration:8s;animation-delay:3s;">&#9733;</span>
                    <!-- paw prints -->
                    <span class="sp sp-paw"
                        style="font-size:28px;left:14%;top:55%;animation-duration:10s;animation-delay:0.8s;"><i
                            class="fas fa-paw"></i></span>
                    <span class="sp sp-paw"
                        style="font-size:18px;left:72%;top:30%;animation-duration:8s;animation-delay:2.5s;"><i
                            class="fas fa-paw"></i></span>
                    <span class="sp sp-paw"
                        style="font-size:14px;left:90%;top:65%;animation-duration:11s;animation-delay:1s;"><i
                            class="fas fa-paw"></i></span>
                    <span class="sp sp-paw"
                        style="font-size:24px;left:38%;top:10%;animation-duration:9s;animation-delay:4s;"><i
                            class="fas fa-paw"></i></span>
                    <!-- hearts -->
                    <span class="sp sp-heart"
                        style="font-size:20px;left:25%;top:78%;animation-duration:8s;animation-delay:2s;">&#10084;</span>
                    <span class="sp sp-heart"
                        style="font-size:14px;left:60%;top:22%;animation-duration:10s;animation-delay:0.5s;">&#10084;</span>
                    <!-- dots -->
                    <span class="sp sp-dot"
                        style="width:8px;height:8px;left:4%;top:40%;animation-duration:7s;animation-delay:3.5s;"></span>
                    <span class="sp sp-dot"
                        style="width:12px;height:12px;left:95%;top:50%;animation-duration:9s;animation-delay:1.2s;"></span>
                    <span class="sp sp-dot-accent"
                        style="width:6px;height:6px;left:45%;top:90%;animation-duration:8s;animation-delay:2.8s;"></span>
                </div>
                <div class="container">
                    <div class="section-header fade-up">
                        <span class="section-badge"><i class="fas fa-comments"></i> Testimonials</span>
                        <h2>What Our Customers Say</h2>
                        <p>Hear from happy pet owners who chose us for their furry friends</p>
                    </div>

                    <div class="testimonial-slider-wrap">
                        <div class="testimonial-slider" id="testimonialSlider">
                            @foreach ($testimonials as $testimonial)
                                <div class="testimonial-slide">
                                    <div class="testimonial-card">
                                        <div class="testimonial-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $testimonial->rating ? 'filled' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="testimonial-content">
                                            <p>"{{ $testimonial->message }}"</p>
                                        </div>
                                        <div class="testimonial-author">
                                            <div class="testimonial-avatar">
                                                @if ($testimonial->image)
                                                    <img src="{{ $testimonial->image_url }}"
                                                        alt="{{ $testimonial->name }}">
                                                @else
                                                    <div class="avatar-placeholder">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="testimonial-info">
                                                <h4>{{ $testimonial->name }}</h4>
                                                @if ($testimonial->designation)
                                                    <span>{{ $testimonial->designation }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($testimonials->count() > 1)
                            <button class="testimonial-slider-nav testimonial-slider-prev"
                                onclick="moveTestimonialSlider(-1)">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="testimonial-slider-nav testimonial-slider-next"
                                onclick="moveTestimonialSlider(1)">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- FAQs SECTION --}}
        @if ($faqs->count())
            <section class="section">
                <div class="container">
                    <div class="section-header fade-up">
                        <span class="section-badge"><i class="fas fa-question-circle"></i> FAQs</span>
                        <h2>Frequently Asked Questions</h2>
                        <p>Find answers to common questions about our pet care services</p>
                    </div>

                    <div class="faq-accordion">
                        @foreach ($faqs as $faq)
                            <div class="faq-item">
                                <button class="faq-question" onclick="toggleFaq(this)">
                                    <span>{{ $faq->question }}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>{{ $faq->answer }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="text-align:center;margin-top:40px;">
                        <a href="#contact" class="btn btn-outline">Still have questions? Contact Us <i
                                class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </section>
        @endif

    </div>
@endsection
@section('scripts')
    <script>
        window.totalSlides = {{ $sliders->count() }};
    </script>



    <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true">
        <div class="gallery-lightbox-panel">
            <div class="gallery-lightbox-image">
                <button type="button" class="gallery-lightbox-close" onclick="closeGalleryLightbox()"
                    aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <img id="galleryLightboxImg" src="" alt="">
            </div>
            <div class="gallery-lightbox-content">
                <div>
                    <span class="section-badge"><i class="fas fa-camera"></i> Gallery Image</span>
                    <h3 id="galleryLightboxTitle"
                        style="font-family:var(--font-display);font-size:2rem;margin:14px 0 10px;"></h3>
                    <p id="galleryLightboxMeta" style="color:var(--clr-gray);margin-bottom:18px;"></p>
                </div>
                <div class="gallery-lightbox-actions">
                    <button type="button" class="gallery-lightbox-nav" onclick="navigateGallery(-1)"
                        aria-label="Previous image"><i class="fas fa-chevron-left"></i></button>
                    <button type="button" class="gallery-lightbox-nav" onclick="navigateGallery(1)"
                        aria-label="Next image"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>
@endsection
