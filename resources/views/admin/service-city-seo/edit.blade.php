@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Edit Service-City SEO Page')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="my-4">
            <h1 class="h3 mb-0">Edit SEO Page</h1>
            <p class="text-muted">{{ $serviceCitySeo->service->title }} in {{ $serviceCitySeo->city->name }}</p>
        </div>

        <form action="{{ route('admin.service-city-seo.update', $serviceCitySeo->id) }}" method="POST" class="row">
            @csrf
            @method('PUT')

            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Service & City (Read-only) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Service & City</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Service</label>
                                <div class="form-control bg-light" disabled>
                                    {{ $serviceCitySeo->service->title }}
                                </div>
                                <small class="text-muted">Cannot be changed after creation</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <div class="form-control bg-light" disabled>
                                    {{ $serviceCitySeo->city->name }}
                                </div>
                                <small class="text-muted">Cannot be changed after creation</small>
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
                                placeholder="Service in City - Brand Name"
                                value="{{ old('seo_title', $serviceCitySeo->seo_title) }}" maxlength="60">
                            <small class="text-muted">
                                Recommended: 50-60 characters
                                <span class="float-end"><span
                                        id="titleCount">{{ strlen(old('seo_title', $serviceCitySeo->seo_title)) }}</span>/60</span>
                            </small>
                            @error('seo_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea name="meta_description" id="meta_description"
                                class="form-control @error('meta_description') is-invalid @enderror" rows="3"
                                placeholder="Write a compelling description for search results..." maxlength="160">{{ old('meta_description', $serviceCitySeo->meta_description) }}</textarea>
                            <small class="text-muted">
                                Recommended: 150-160 characters
                                <span class="float-end"><span
                                        id="descCount">{{ strlen(old('meta_description', $serviceCitySeo->meta_description)) }}</span>/160</span>
                            </small>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meta Keywords -->
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <textarea name="meta_keywords" id="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror"
                                rows="2" placeholder="keyword1, keyword2, keyword3..." maxlength="255">{{ old('meta_keywords', $serviceCitySeo->meta_keywords) }}</textarea>
                            <small class="text-muted">
                                Separate keywords with commas (max 255 characters)
                                <span class="float-end"><span
                                        id="keyCount">{{ strlen(old('meta_keywords', $serviceCitySeo->meta_keywords)) }}</span>/255</span>
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
                        <textarea name="custom_content" id="custom_content" class="form-control @error('custom_content') is-invalid @enderror"
                            rows="8" placeholder="Add custom HTML content specific to this service-city combination...">{{ old('custom_content', $serviceCitySeo->custom_content) }}</textarea>
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
                                class="form-control @error('priority') is-invalid @enderror" min="0" max="100"
                                value="{{ old('priority', $serviceCitySeo->priority) }}">
                            <small class="text-muted">Higher priority = appears first in related lists</small>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                value="1" {{ old('is_active', $serviceCitySeo->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Page
                            </label>
                            <small class="text-muted d-block">Inactive pages will not be accessible or indexed</small>
                        </div>
                    </div>
                </div>

                <!-- Page Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Page Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Page Slug:</small>
                            <code class="text-break">{{ $serviceCitySeo->slug }}</code>
                        </div>

                        <!-- Auto-Generated URL -->
                        <div class="mb-3">
                            <small class="text-muted d-block">Auto-Generated URL:</small>
                            <a href="{{ route('services.city', [
                                'serviceSlug' => $serviceCitySeo->service->slug,
                                'citySlug' => Str::slug($serviceCitySeo->city->name),
                            ]) }}"
                                target="_blank" class="text-break small">
                                {{ route('services.city', [
                                    'serviceSlug' => $serviceCitySeo->service->slug,
                                    'citySlug' => Str::slug($serviceCitySeo->city->name),
                                ]) }}
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>

                        <!-- Custom URL Editor -->
                        <div class="mb-3">
                            <label for="custom_url" class="form-label">Custom Page URL</label>
                            <input type="text" name="custom_url" id="custom_url" class="form-control"
                                placeholder="pet-vaccination-in-home-pet-services-mohali-ss"
                                value="{{ old('custom_url', $serviceCitySeo->custom_url) }}">
                            <small class="text-muted d-block mt-2">
                                <strong>Note:</strong> If you set a custom URL, it will override the auto-generated URL.
                                This is useful for custom domain configurations or URL redirections.
                            </small>
                            @error('custom_url')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Active URL -->
                        <div class="mb-3">
                            <small class="text-muted d-block">Current Active URL:</small>
                            <a href="{{ $serviceCitySeo->url }}" target="_blank" class="text-break">
                                {{ $serviceCitySeo->url }}
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>

                        <div class="mb-0">
                            <small class="text-muted d-block">Created:</small>
                            <small>{{ $serviceCitySeo->created_at->format('M d, Y H:i') }}</small>
                        </div>
                        <div>
                            <small class="text-muted d-block">Last Updated:</small>
                            <small>{{ $serviceCitySeo->updated_at->format('M d, Y H:i') }}</small>
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
                            <!-- FAQs will be loaded dynamically -->
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
                                {{ $serviceCitySeo->seo_title ?: 'Your SEO Title' }}
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Description in Search Results:</small>
                            <div class="p-2 bg-light rounded" id="previewDesc"
                                style="max-height: 60px; overflow: hidden;">
                                {{ $serviceCitySeo->meta_description ?: 'Your meta description' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.service-city-seo.show', $serviceCitySeo->id) }}"
                        class="btn btn-outline-info btn-lg">
                        <i class="bi bi-eye"></i> View Page
                    </a>
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
                        document.getElementById('previewTitle').textContent = this.value ||
                            'Your SEO Title';
                    }
                    if (id === 'meta_description') {
                        document.getElementById('previewDesc').textContent = this.value ||
                            'Your meta description';
                    }
                });
            });

            // Load existing FAQs
            const existingFaqs = @json($serviceCitySeo->faq ?? []);
            let faqCount = 0;

            if (existingFaqs && existingFaqs.length > 0) {
                const container = document.getElementById('faqContainer');
                existingFaqs.forEach((faq, index) => {
                    const faqItem = document.createElement('div');
                    faqItem.className = 'mb-3 p-3 bg-light rounded';
                    faqItem.id = 'faq-' + faqCount;
                    faqItem.innerHTML = `
                <div class="mb-2">
                    <input type="text" class="form-control faq-question" placeholder="Question" value="${faq.question || ''}" required>
                </div>
                <div class="mb-2">
                    <textarea class="form-control faq-answer" placeholder="Answer" rows="2" required>${faq.answer || ''}</textarea>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove(); updateFaqJson();">
                    Remove
                </button>
            `;
                    container.appendChild(faqItem);
                    faqCount++;
                });
            }

            // FAQ Management
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
                        faqs.push({
                            question,
                            answer
                        });
                    }
                });
                document.getElementById('faq_content').value = JSON.stringify(faqs);
            };

            // Update FAQ JSON on form submit
            document.querySelector('form').addEventListener('submit', function() {
                updateFaqJson();
            });

            // Initialize with existing data
            updateFaqJson();
        });
    </script>

    <style>
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
@endsection
