@extends('layouts.admin')

@section('title', 'View Service-City SEO Page')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="my-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">{{ $serviceCitySeo->service->title }} in {{ $serviceCitySeo->city->name }}</h1>
            <p class="text-muted">Service-City SEO Page Details</p>
        </div>
        <div>
            <a href="{{ route('admin.service-city-seo.edit', $serviceCitySeo->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.service-city-seo.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- URL & Accessibility -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">🔗 Page Access</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Page Slug:</small>
                        <code class="d-block p-2 bg-light rounded">{{ $serviceCitySeo->slug }}</code>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Live URL:</small>
                        <div class="p-2 bg-light rounded">
                            <a href="{{ $serviceCitySeo->url }}" target="_blank" class="text-decoration-none">
                                {{ url($serviceCitySeo->slug) }}
                                <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="badge me-2 {{ $serviceCitySeo->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $serviceCitySeo->is_active ? '✓ Active' : '✗ Inactive' }}
                        </div>
                        <div class="badge bg-info">Priority: {{ $serviceCitySeo->priority }}</div>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📊 SEO Information</h5>
                </div>
                <div class="card-body">
                    <!-- SEO Title -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">SEO Title ({{ strlen($serviceCitySeo->seo_title) }}/60)</h6>
                        <div class="p-3 bg-light rounded border-start border-primary" style="border-left-width: 4px !important;">
                            <strong>{{ $serviceCitySeo->seo_title }}</strong>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            How it appears in search results
                        </small>
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Meta Description ({{ strlen($serviceCitySeo->meta_description) }}/160)</h6>
                        <div class="p-3 bg-light rounded border-start border-info" style="border-left-width: 4px !important;">
                            {{ $serviceCitySeo->meta_description }}
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Preview of your page in search results
                        </small>
                    </div>

                    <!-- Meta Keywords -->
                    <div class="mb-0">
                        <h6 class="text-muted mb-2">Meta Keywords ({{ strlen($serviceCitySeo->meta_keywords) }}/255)</h6>
                        <div class="p-3 bg-light rounded border-start border-warning" style="border-left-width: 4px !important;">
                            @if($serviceCitySeo->meta_keywords)
                                @php
                                    $keywords = array_map('trim', explode(',', $serviceCitySeo->meta_keywords));
                                @endphp
                                @foreach($keywords as $keyword)
                                    <span class="badge bg-light text-dark me-2 mb-2">{{ $keyword }}</span>
                                @endforeach
                            @else
                                <em class="text-muted">No keywords defined</em>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Content -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📝 Custom Content</h5>
                </div>
                <div class="card-body">
                    @if($serviceCitySeo->custom_content)
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(htmlspecialchars($serviceCitySeo->custom_content)) !!}
                        </div>
                    @else
                        <em class="text-muted">No custom content added</em>
                    @endif
                </div>
            </div>

            <!-- FAQ Section -->
            @if($serviceCitySeo->faq && count($serviceCitySeo->faq) > 0)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">❓ FAQ Items ({{ count($serviceCitySeo->faq) }})</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        @foreach($serviceCitySeo->faq as $index => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}">
                                    {{ $faq['question'] }}
                                </button>
                            </h2>
                            <div id="faq{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {!! nl2br(htmlspecialchars($faq['answer'])) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Schema Preview -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">🏷️ Schema.org Markup</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        <i class="bi bi-info-circle"></i>
                        This page includes the following schema.org markup types for better search engine visibility:
                    </p>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="badge bg-primary p-2 w-100">
                                <i class="bi bi-diagram-3"></i> Breadcrumb Schema
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="badge bg-primary p-2 w-100">
                                <i class="bi bi-building"></i> LocalBusiness Schema
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="badge bg-primary p-2 w-100">
                                <i class="bi bi-question-circle"></i> FAQ Schema
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="badge bg-primary p-2 w-100">
                                <i class="bi bi-globe"></i> Organization Schema
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="badge bg-primary p-2 w-100">
                                <i class="bi bi-star"></i> Rating Schema
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Page Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">ℹ️ Page Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Service</small>
                        <strong>{{ $serviceCitySeo->service->title }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">City</small>
                        <strong>{{ $serviceCitySeo->city->name }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge {{ $serviceCitySeo->is_active ? 'bg-success' : 'bg-warning' }}">
                            {{ $serviceCitySeo->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Priority</small>
                        <strong>{{ $serviceCitySeo->priority }}/100</strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Content Length</small>
                        <strong>{{ strlen($serviceCitySeo->custom_content) }} characters</strong>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">⏰ Timestamps</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Created</small>
                        <small>
                            {{ $serviceCitySeo->created_at->format('M d, Y') }}
                            <br>
                            <code class="text-muted">{{ $serviceCitySeo->created_at->format('H:i:s') }}</code>
                        </small>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Last Updated</small>
                        <small>
                            {{ $serviceCitySeo->updated_at->format('M d, Y') }}
                            <br>
                            <code class="text-muted">{{ $serviceCitySeo->updated_at->format('H:i:s') }}</code>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📈 Content Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="text-muted small">SEO Title</div>
                            <div class="h5">{{ strlen($serviceCitySeo->seo_title) }}</div>
                            <small class="text-muted">/60 chars</small>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Description</div>
                            <div class="h5">{{ strlen($serviceCitySeo->meta_description) }}</div>
                            <small class="text-muted">/160 chars</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-muted small">Keywords</div>
                            <div class="h5">{{ strlen($serviceCitySeo->meta_keywords) }}</div>
                            <small class="text-muted">/255 chars</small>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">FAQ Items</div>
                            <div class="h5">{{ count($serviceCitySeo->faq ?? []) }}</div>
                            <small class="text-muted">items</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">⚙️ Actions</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ $serviceCitySeo->url }}" target="_blank" class="btn btn-info btn-sm">
                        <i class="bi bi-box-arrow-up-right"></i> View on Frontend
                    </a>
                    <a href="{{ route('admin.service-city-seo.edit', $serviceCitySeo->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit Page
                    </a>
                    <form action="{{ route('admin.service-city-seo.destroy', $serviceCitySeo->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');" class="d-grid">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Delete Page
                        </button>
                    </form>
                </div>
            </div>

            <!-- SEO Checklist -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">✅ SEO Checklist</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" disabled {{ $serviceCitySeo->seo_title ? 'checked' : '' }}>
                        <label class="form-check-label">
                            SEO Title filled
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" disabled {{ $serviceCitySeo->meta_description ? 'checked' : '' }}>
                        <label class="form-check-label">
                            Meta description filled
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" disabled {{ $serviceCitySeo->meta_keywords ? 'checked' : '' }}>
                        <label class="form-check-label">
                            Meta keywords filled
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" disabled {{ $serviceCitySeo->custom_content ? 'checked' : '' }}>
                        <label class="form-check-label">
                            Custom content added
                        </label>
                    </div>
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" disabled {{ count($serviceCitySeo->faq ?? []) > 0 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            FAQ items added
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
    }

    .badge {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        font-weight: 500;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #212529;
    }
</style>
@endsection
