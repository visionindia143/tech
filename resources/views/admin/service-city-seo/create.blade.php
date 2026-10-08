@extends('layouts.admin')

@section('title', 'Create Service-City SEO Page')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="my-4">
        <h1 class="h3 mb-0">Create SEO Page</h1>
        <p class="text-muted">Create a new service-city landing page with custom SEO content</p>
    </div>

    <form action="{{ route('admin.service-city-seo.store') }}" method="POST" class="row">
        @csrf

        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Service & City -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Service & City Selection</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="service_id" class="form-label">Service *</label>
                            <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                                <option value="">-- Select Service --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city_id" class="form-label">City *</label>
                            <select name="city_id" id="city_id" class="form-select @error('city_id') is-invalid @enderror" required>
                                <option value="">-- Select City --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Meta Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">SEO Meta Information</h5>
                </div>
                <div class="card-body">
                    <!-- SEO Title -->
                    <div class="mb-3">
                        <label for="seo_title" class="form-label">SEO Title</label>
                        <input type="text" name="seo_title" id="seo_title" 
                               class="form-control @error('seo_title') is-invalid @enderror"
                               placeholder="Service in City - Brand Name (auto-generated if empty)"
                               value="{{ old('seo_title') }}" maxlength="60">
                        <small class="text-muted">
                            Recommended: 50-60 characters
                            <span class="float-end"><span id="titleCount">0</span>/60</span>
                        </small>
                        @error('seo_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" 
                                  class="form-control @error('meta_description') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Write a compelling description for search results..."
                                  maxlength="160">{{ old('meta_description') }}</textarea>
                        <small class="text-muted">
                            Recommended: 150-160 characters
                            <span class="float-end"><span id="descCount">0</span>/160</span>
                        </small>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Meta Keywords -->
                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <textarea name="meta_keywords" id="meta_keywords" 
                                  class="form-control @error('meta_keywords') is-invalid @enderror"
                                  rows="2"
                                  placeholder="keyword1, keyword2, keyword3..."
                                  maxlength="255">{{ old('meta_keywords') }}</textarea>
                        <small class="text-muted">
                            Separate keywords with commas (max 255 characters)
                            <span class="float-end"><span id="keyCount">0</span>/255</span>
                        </small>
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Custom Content -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Custom Content</h5>
                </div>
                <div class="card-body">
                    <label for="custom_content" class="form-label">Page Content (HTML allowed)</label>
                    <textarea name="custom_content" id="custom_content" 
                              class="form-control @error('custom_content') is-invalid @enderror"
                              rows="8"
                              placeholder="Add custom HTML content specific to this service-city combination...">{{ old('custom_content') }}</textarea>
                    <small class="text-muted d-block mt-2">
                        ✓ HTML/Markdown allowed | ✓ Include city-specific information | ✓ Add testimonials or offers
                    </small>
                    @error('custom_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Priority & Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority (0-100)</label>
                        <input type="number" name="priority" id="priority" 
                               class="form-control @error('priority') is-invalid @enderror"
                               min="0" max="100" value="{{ old('priority', 0) }}">
                        <small class="text-muted">Higher priority = appears first in related lists</small>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" 
                               class="form-check-input" value="1" 
                               {{ old('is_active', false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active Page
                        </label>
                        <small class="text-muted d-block">Inactive pages will not be accessible or indexed</small>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">FAQ Items</h5>
                </div>
                <div class="card-body">
                    <div id="faqContainer">
                        <!-- FAQs will be added dynamically -->
                    </div>
                    <button type="button" id="addFaqBtn" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                        <i class="bi bi-plus"></i> Add FAQ
                    </button>
                    <input type="hidden" name="faq_content" id="faq_content" value="">
                </div>
            </div>

            <!-- Preview -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Preview</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Title in Search Results:</small>
                        <div class="p-2 bg-light rounded text-truncate" id="previewTitle">
                            Your SEO Title
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">Description in Search Results:</small>
                        <div class="p-2 bg-light rounded" id="previewDesc" style="max-height: 60px; overflow: hidden;">
                            Your meta description
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Create Page
                </button>
                <a href="{{ route('admin.service-city-seo.index') }}" class="btn btn-outline-secondary btn-lg">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counters
    ['seo_title', 'meta_description', 'meta_keywords'].forEach(id => {
        const elem = document.getElementById(id);
        const countId = id + 'Count';
        
        elem.addEventListener('input', function() {
            document.getElementById(countId).textContent = this.value.length;
            if (id === 'seo_title') {
                document.getElementById('previewTitle').textContent = this.value || 'Your SEO Title';
            }
            if (id === 'meta_description') {
                document.getElementById('previewDesc').textContent = this.value || 'Your meta description';
            }
        });
        
        // Initialize counts
        document.getElementById(countId).textContent = elem.value.length;
    });

    // FAQ Management
    let faqCount = 0;
    
    document.getElementById('addFaqBtn').addEventListener('click', function() {
        const container = document.getElementById('faqContainer');
        const faqItem = document.createElement('div');
        faqItem.className = 'mb-3 p-3 bg-light rounded';
        faqItem.id = 'faq-' + faqCount;
        faqItem.innerHTML = `
            <div class="mb-2">
                <input type="text" class="form-control faq-question" placeholder="Question" required>
            </div>
            <div class="mb-2">
                <textarea class="form-control faq-answer" placeholder="Answer" rows="2" required></textarea>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove(); updateFaqJson();">
                Remove
            </button>
        `;
        container.appendChild(faqItem);
        faqCount++;
    });

    window.updateFaqJson = function() {
        const faqs = [];
        document.querySelectorAll('#faqContainer > div').forEach(item => {
            const question = item.querySelector('.faq-question').value;
            const answer = item.querySelector('.faq-answer').value;
            if (question && answer) {
                faqs.push({ question, answer });
            }
        });
        document.getElementById('faq_content').value = JSON.stringify(faqs);
    };

    // Update FAQ JSON on form submit
    document.querySelector('form').addEventListener('submit', function() {
        updateFaqJson();
    });
});
</script>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
@endsection
