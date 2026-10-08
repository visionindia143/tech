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
        .st-card-section .st-container {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
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



        /* ─────────────────────────────────────────────────────────── */
    </style>
@endsection

@section('content')
 <div class="page-hero">
        <div class="container">
            <h1>All Services</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a> / Services
            </div>
        </div>
    </div>
    <div style="position: relative; overflow: hidden;">
        {{-- FLOATING PET PARTICLES --}}
        <div class="pet-particles" id="petParticles"></div>

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

    </div>
@section('scripts')


    <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true">
        <div class="gallery-lightbox-panel">
            <div class="gallery-lightbox-image">
                <button type="button" class="gallery-lightbox-close" onclick="closeGalleryLightbox()" aria-label="Close">
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
