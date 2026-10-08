@extends('layouts.frontend')


@section('title', $page->seo_title)
@section('description', $page->meta_description)
@section('keywords', $page->meta_keywords)

@section('canonical')
    <link rel="canonical" href="{{ $page->url }}" />
     <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
@endsection

@section('schema')
    <!-- Breadcrumb Schema -->
    <script type="application/ld+json">
        {!! App\Services\SchemaMarkupService::toJsonLd(App\Services\SchemaMarkupService::breadcrumbSchema($page)) !!}
    </script>

    <!-- LocalBusiness Schema -->
    <script type="application/ld+json">
        {!! App\Services\SchemaMarkupService::toJsonLd(App\Services\SchemaMarkupService::localBusinessSchema($page)) !!}
    </script>

    <!-- Organization Schema -->
    <script type="application/ld+json">
        {!! App\Services\SchemaMarkupService::toJsonLd(App\Services\SchemaMarkupService::organizationSchema()) !!}
    </script>

    @if (!empty($page->faq))
        <!-- FAQ Schema -->
        <script type="application/ld+json">
            {!! App\Services\SchemaMarkupService::toJsonLd(App\Services\SchemaMarkupService::faqSchema($page)) !!}
        </script>
    @endif

    <!-- AggregateRating Schema -->
    <script type="application/ld+json">
        {!! App\Services\SchemaMarkupService::toJsonLd(App\Services\SchemaMarkupService::aggregateRatingSchema()) !!}
    </script>
@endsection

@section('content')
    <div class="service-city-page">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="breadcrumb-section">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('service.show', $service->slug) }}">{{ $service->title }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('city.detail', $city->slug) }}">{{ $city->name }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $service->title }} in {{ $city->name }}
                    </li>
                </ol>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="page-title">{{ $page->seo_title }}</h1>
                        <p class="page-subtitle">Professional {{ $service->title }} services in {{ $city->name }}</p>
                        <a href="#booking" class="btn btn-primary btn-lg mt-4">Book Appointment</a>
                    </div>
                    <div class="col-md-6">
                        @if ($service->image)
                            <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="img-fluid rounded">
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content-section">
            <div class="container">
                <div class="row">
                    <!-- Primary Content -->
                    <div class="col-lg-8">
                        <!-- Meta Description -->
                        <div class="alert alert-info">
                            {{ $page->meta_description }}
                        </div>

                        <!-- Service Description -->
                        @if ($service->description)
                            <div class="service-description">
                                <h2>About {{ $service->title }} in {{ $city->name }}</h2>
                                {!! $service->description !!}
                            </div>
                        @endif

                        <!-- Custom SEO Content -->
                        @if ($page->custom_content)
                            <div class="custom-content mt-4">
                                {!! $page->custom_content !!}
                            </div>
                        @endif

                        <!-- FAQ Section -->
                        @if (!empty($page->faq))
                            <div class="faq-section mt-5">
                                <h2>Frequently Asked Questions</h2>
                                <div class="accordion" id="faqAccordion">
                                    @foreach ($page->faq as $index => $faq)
                                        <div class="accordion-item">
                                            <h3 class="accordion-header">
                                                <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#faq{{ $index }}"
                                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                                    {{ $faq['question'] }}
                                                </button>
                                            </h3>
                                            <div id="faq{{ $index }}"
                                                class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                                data-bs-parent="#faqAccordion">
                                                <div class="accordion-body">
                                                    {!! $faq['answer'] !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <aside class="col-lg-4">
                        <!-- Service Card -->
                        <div class="card service-card shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title">{{ $service->title }}</h3>
                                <p class="card-text text-muted">{{ $service->short_description ?? $service->description }}
                                </p>

                                {{-- @if ($service->price)
                                    <div class="price-badge mb-3">
                                        <strong>Starting from: </strong>₹{{ number_format($service->price, 2) }}
                                    </div>
                                @endif --}}

                                <a href="#booking" class="btn btn-primary w-100">Book Now</a>
                            </div>
                        </div>

                        <!-- City Information -->
                        <div class="card city-card shadow-sm mt-4">
                            <div class="card-body">
                                <h4 class="card-title">{{ $city->name }}</h4>
                                @if ($city->description)
                                    <p class="card-text">{{ Str::limit($city->description, 150) }}</p>
                                @endif
                                <a href="{{ route('city.detail', $city->slug) }}"
                                    class="btn btn-outline-primary btn-sm w-100">
                                    Explore {{ $city->name }}
                                </a>
                            </div>
                        </div>

                        <!-- Related Services in Same City -->
                        @if ($relatedServices->isNotEmpty())
                            <div class="card related-services mt-4 shadow-sm">
                                <div class="card-header">
                                    <h5>Other Services in {{ $city->name }}</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        @foreach ($relatedServices as $related)
                                            <li class="mb-2">
                                                <a href="{{ $related->url }}" class="text-decoration-none">
                                                    {{ $related->service->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <!-- Related Cities for Same Service -->
                        @if ($relatedCities->isNotEmpty())
                            <div class="card related-cities mt-4 shadow-sm">
                                <div class="card-header">
                                    <h5>{{ $service->title }} in Other Cities</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        @foreach ($relatedCities as $related)
                                            <li class="mb-2">
                                                <a href="{{ $related->url }}" class="text-decoration-none">
                                                    {{ $related->city->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </aside>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section bg-light py-5" id="booking">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-center">
                        <h2>Ready to Book {{ $service->title }}?</h2>
                        <p class="lead text-white ">Schedule your appointment today and experience our professional services.
                        </p>

                        @if ($service->button_url)
                            <a href="{{ $service->button_url }}" class="btn btn-primary btn-lg">
                                {{ $service->button_label ?? 'Book Now' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Meta Keywords Display (for reference) -->
        @if ($page->meta_keywords)
            <div class="keywords-section d-none">
                <meta name="keywords" content="{{ $page->meta_keywords }}">
            </div>
        @endif
    </div>

    <style>
        .service-city-page {
            min-height: 100vh;
        }

        .breadcrumb-section {
            background-color: #f8f9fa;
            padding: 1rem 0;
            border-bottom: 1px solid #dee2e6;
        }

        .breadcrumb {
            margin-bottom: 0;
            background-color: transparent;
            padding: 0;
        }

        .hero-section {
            padding: 4rem 0;
            color: white;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 1.5rem;
        }

        .content-section {
            padding: 3rem 0;
        }

        .service-description {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .service-description h2 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.75rem;
        }

        .custom-content {
            line-height: 1.8;
            color: #555;
        }

        .faq-section {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
        }

        .faq-section h2 {
            margin-bottom: 2rem;
            color: #333;
        }

        .accordion-button:not(.collapsed) {
            background-color: #667eea;
            color: white;
        }

        .service-card,
        .city-card {
            border: none;
            border-top: 3px solid #667eea;
        }

        .price-badge {
            background-color: #f0f4ff;
            padding: 1rem;
            border-radius: 4px;
            color: #667eea;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #667eea;
            padding: 1rem;
        }

        .card-header h5 {
            margin: 0;
            color: #333;
        }

        .cta-section {
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.75rem;
            }

            .page-subtitle {
                font-size: 1rem;
            }

            .hero-section {
                padding: 2rem 0;
            }
        }
    </style>
    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@endsection
@endsection
