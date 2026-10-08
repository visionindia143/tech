@extends('layouts.frontend')

@section('meta_title', $city->meta_title ?: ('Pet Care Services in ' . $city->name . ' | ' . $settings['site_name']))
@section('meta_description', $city->meta_description ?: ('Professional pet care, grooming, vaccination and support services in ' . $city->name . '.'))
@section('meta_keywords', $city->meta_keywords ?: ('pet care ' . strtolower($city->name) . ', pet grooming ' . strtolower($city->name) . ', pet vaccination ' . strtolower($city->name)))
@section('canonical', route('city.detail', $city->slug))
@section('og_image', $city->image_url ?: asset('images/og-default.jpg'))

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Pet Care Services in {{ $city->name }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> / <span>{{ $city->name }}</span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="city-detail-grid">
            <div class="fade-up">
                @if($city->image)
                    <img src="{{ $city->image_url }}" alt="Pet care services in {{ $city->name }}" loading="lazy"
                         style="width:100%;border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);object-fit:cover;max-height:420px;">
                @else
                    <div style="width:100%;height:360px;background:linear-gradient(135deg,var(--clr-primary),var(--clr-accent));border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-city" style="font-size:6rem;color:#fff;opacity:.75;"></i>
                    </div>
                @endif
            </div>
            <div class="fade-up">
                <span class="section-badge"><i class="fas fa-map-marker-alt"></i> City Service Area</span>
                <h2 style="font-family:var(--font-display);font-size:2.1rem;line-height:1.2;margin:16px 0 16px;">{{ $city->name }} Pet Care</h2>
                <p style="font-size:1.03rem;color:var(--clr-gray);line-height:1.8;">
                    {{ $city->description ?: ('PawCare provides trusted pet care and vaccination support in ' . $city->name . '. Book appointments, explore services, and connect with our care team in your city.') }}
                </p>

                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:22px;">
                    <a href="{{ route('appointment') }}" class="btn btn-primary"><i class="fas fa-syringe"></i> Book Appointment</a>
                    <a href="{{ route('contact') }}" class="btn btn-outline"><i class="fas fa-phone"></i> Contact Team</a>
                </div>
            </div>
        </div>
    </div>
</section>

@if($services->count())
<section class="section" style="background:var(--clr-light-gray);">
    <div class="container">
        <div class="section-header fade-up">
            <span class="section-badge"><i class="fas fa-heart"></i> Services in {{ $city->name }}</span>
            <h2>Popular Pet Care Services</h2>
            <p>Handpicked services frequently booked by pet parents in {{ $city->name }}</p>
        </div>
        <div class="services-grid">
            @foreach($services as $service)
                <a href="{{ route('service.show', $service->slug) }}" class="service-card fade-up" style="text-decoration:none;display:block;">
                    <div class="service-icon">
                        <i class="{{ $service->icon ?: 'fas fa-paw' }}"></i>
                    </div>
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->short_description }}</p>
                    @if($service->price)
                        <div class="service-price">From ₹{{ number_format($service->price) }}</div>
                    @endif
                    <span style="color:var(--clr-primary);font-size:0.88rem;font-weight:600;display:inline-block;margin-top:8px;">Learn More <i class="fas fa-arrow-right"></i></span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($vaccinationTypes->count())
<section class="section">
    <div class="container">
        <div class="section-header fade-up">
            <span class="section-badge"><i class="fas fa-syringe"></i> Vaccinations</span>
            <h2>Vaccination Support in {{ $city->name }}</h2>
            <p>Core pet vaccinations available with appointment booking</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;">
            @foreach($vaccinationTypes as $type)
                <div class="panel fade-up" style="padding:20px;">
                    <h3 style="font-size:1.05rem;margin-bottom:8px;">{{ $type->name }}</h3>
                    @if($type->description)
                        <p style="color:var(--clr-gray);font-size:0.92rem;line-height:1.6;">{{ $type->description }}</p>
                    @endif
                    @if($type->price)
                        <div style="margin-top:10px;font-weight:700;color:var(--clr-primary);">₹{{ number_format($type->price) }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "{{ $settings['site_name'] }} - {{ $city->name }}",
    "url": "{{ route('city.detail', $city->slug) }}",
    "description": "{{ $city->meta_description ?: ($city->description ?: ('Pet care and vaccination services in ' . $city->name)) }}",
    "areaServed": "{{ $city->name }}"
}
</script>
@endsection
