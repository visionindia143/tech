@extends('layouts.frontend')

@section('meta_title', $service->title . ' - ' . $settings['site_name'])
@section('meta_description', $service->short_description ?? '')

@section('content')
    <div class="page-hero">
        <div class="container">
            <h1>{{ $service->title }}</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('home') }}#services">Services</a> /
                {{ $service->title }}
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="service-detail-grid">

                {{-- Service Image --}}
                <div class="fade-up">
                    @if ($service->image)
                        <img src="{{ $service->image_url }}" alt="{{ $service->title }}"
                            style="width:100%;border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);object-fit:cover;max-height:460px;">
                    @else
                        <div
                            style="width:100%;height:360px;background:linear-gradient(135deg,var(--clr-warm),var(--clr-primary));border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;">
                            <i class="{{ $service->icon ?: 'fas fa-paw' }}"
                                style="font-size:6rem;color:var(--clr-primary);opacity:.4;"></i>
                        </div>
                    @endif
                </div>

                {{-- Service Info --}}
                <div class="fade-up">
                    <div
                        style="display:inline-flex;align-items:center;gap:10px;background:var(--clr-warm);padding:10px 20px;border-radius:50px;margin-bottom:20px;margin-top:20px;">
                        <i class="{{ $service->icon ?: 'fas fa-paw' }}"
                            style="color:var(--clr-primary);font-size:1.2rem;"></i>
                        <span style="font-weight:600;color:var(--clr-primary);">Pet Care Service</span>
                    </div>

                    {{-- <h2 style="font-family:var(--font-display);font-size:2.2rem;line-height:1.2;margin-bottom:16px;">{{ $service->title }}</h2> --}}

                    @if ($service->short_description)
                        <p style="font-size:1.1rem;color:var(--clr-gray);margin-bottom:14px;">
                            {{ $service->short_description }}</p>
                    @endif

                    {{-- Full Description --}}
                    @if ($service->description)
                        <div class="fade-up" style="margin-top:10px;">
                            <div class="panel" style="padding:10px;">
                                <h3
                                    style="font-family:var(--font-display);font-size:1.6rem;margin-bottom:24px;color:var(--clr-secondary);">
                                    About This Service
                                </h3>
                                <div class="prose" style="color:var(--clr-dark);line-height:1.9;font-size:1rem;">
                                    {!! $service->description !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- @if ($service->price)
                    <div style="background:var(--clr-warm);border-radius:var(--radius-md);padding:16px 24px;margin-bottom:28px;display:inline-block;">
                        <span style="font-size:0.85rem;color:var(--clr-gray);">Starting from</span>
                        <div style="font-size:2rem;font-weight:800;color:var(--clr-primary);">₹{{ number_format($service->price) }}</div>
                    </div>
                @endif --}}

                    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:24px;">
                        <a href="{{ $service->button_url }}" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i>{{ $service->button_label }}
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline">
                            <i class="fas fa-phone"></i> Contact Us
                        </a>
                    </div>
                </div>
            </div>



            {{-- Related Services --}}
            @if ($related->count())
                <div class="fade-up" style="margin-top:60px;">
                    <div class="section-header" style="text-align:left;">
                        <span class="section-badge"><i class="fas fa-concierge-bell"></i> More Services</span>
                        <h3 style="font-family:var(--font-display);font-size:1.6rem;margin-top:10px;">Other Services We
                            Offer</h3>
                    </div>
                    <div class="services-grid" style="margin-top:24px;">
                        @foreach ($related as $rel)
                            <a href="{{ route('service.show', $rel->slug) }}" class="service-card fade-up"
                                style="text-decoration:none;display:block;">
                                <div class="service-icon">
                                    <i class="{{ $rel->icon ?: 'fas fa-paw' }}"></i>
                                </div>
                                <h3>{{ $rel->title }}</h3>
                                <p>{{ $rel->short_description }}</p>

                                {{-- @if ($rel->price)
                        <div class="service-price">From ₹{{ number_format($rel->price) }}</div>
                    @endif --}}
                                <span
                                    style="color:var(--clr-primary);font-size:0.85rem;font-weight:600;margin-top:8px;display:inline-block;">Learn
                                    More <i class="fas fa-arrow-right"></i></span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
