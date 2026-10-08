@extends('layouts.frontend')

@section('meta_title', $blog->meta_title ?: $blog->title)
@section('meta_description', $blog->meta_description ?: $blog->excerpt)
@section('meta_keywords', $blog->meta_keywords)
@section('canonical', route('blog.detail', $blog->slug))
@section('og_image', $blog->image_url)

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>{{ $blog->title }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> / <a href="{{ route('blogs') }}">Blog</a> / {{ Str::limit($blog->title, 40) }}
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="blog-detail">
            @if($blog->image)
                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="blog-detail-img" loading="lazy">
            @endif

            <div class="blog-detail-meta">
                <span><i class="far fa-calendar"></i> {{ $blog->created_at->format('F d, Y') }}</span>
                <span><i class="far fa-eye"></i> {{ number_format($blog->views) }} views</span>
                <span><i class="far fa-clock"></i> {{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</span>
            </div>

            <div class="blog-content">
                {!! $blog->content !!}
            </div>

            {{-- Share Buttons --}}
            <div style="margin-top:40px;padding-top:30px;border-top:1px solid var(--clr-border);">
                <p style="font-weight:600;margin-bottom:12px;">Share this article:</p>
                <div class="blog-share-row" style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.detail', $blog->slug)) }}" target="_blank" rel="noopener" class="btn btn-outline" style="padding:10px 20px;font-size:0.85rem;">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.detail', $blog->slug)) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener" class="btn btn-outline" style="padding:10px 20px;font-size:0.85rem;">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . route('blog.detail', $blog->slug)) }}" target="_blank" rel="noopener" class="btn btn-outline" style="padding:10px 20px;font-size:0.85rem;">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>

        {{-- Related Posts --}}
        @if($relatedPosts->count())
        <div style="margin-top:80px;">
            <div class="section-header">
                <span class="section-badge">Related Posts</span>
                <h2>You May Also Like</h2>
            </div>
            <div class="blog-grid">
                @foreach($relatedPosts as $post)
                <a href="{{ route('blog.detail', $post->slug) }}" class="blog-card fade-up">
                    <div class="blog-card-img">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy">
                    </div>
                    <div class="blog-card-body">
                        <div class="blog-card-date">{{ $post->created_at->format('M d, Y') }}</div>
                        <h3>{{ $post->title }}</h3>
                        <p>{{ $post->excerpt }}</p>
                        <span class="blog-card-link">Read More <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- JSON-LD Structured Data --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "{{ $blog->title }}",
    "description": "{{ $blog->meta_description ?: $blog->excerpt }}",
    "image": "{{ $blog->image_url }}",
    "datePublished": "{{ $blog->created_at->toIso8601String() }}",
    "dateModified": "{{ $blog->updated_at->toIso8601String() }}",
    "url": "{{ route('blog.detail', $blog->slug) }}",
    "publisher": {
        "@type": "Organization",
        "name": "{{ $settings['site_name'] }}"
    }
}
</script>
@endsection
