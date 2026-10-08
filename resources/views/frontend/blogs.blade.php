@extends('layouts.frontend')

@section('meta_title', 'Pet Care Blog - Tips, Guides & Stories')
@section('meta_description', 'Read our latest blog posts about pet care tips, health guides, grooming advice and heartwarming pet stories.')

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Pet Care Blog</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> / Blog
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        {{-- Search Bar --}}
        <form action="{{ route('blogs') }}" method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search blog posts..." value="{{ request('search') }}">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>

        @if($blogs->count())
            <div class="blog-grid">
                @foreach($blogs as $blog)
                <a href="{{ route('blog.detail', $blog->slug) }}" class="blog-card fade-up">
                    <div class="blog-card-img">
                        <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" loading="lazy">
                    </div>
                    <div class="blog-card-body">
                        <div class="blog-card-date">{{ $blog->created_at->format('M d, Y') }}</div>
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ $blog->excerpt }}</p>
                        <span class="blog-card-link">Read More <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($blogs->hasPages())
            <div class="pagination-wrap">
                @if($blogs->onFirstPage())
                    <span class="page-link" style="opacity:0.4">&laquo;</span>
                @else
                    <a href="{{ $blogs->previousPageUrl() }}" class="page-link">&laquo;</a>
                @endif

                @foreach($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-link {{ $blogs->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if($blogs->hasMorePages())
                    <a href="{{ $blogs->nextPageUrl() }}" class="page-link">&raquo;</a>
                @else
                    <span class="page-link" style="opacity:0.4">&raquo;</span>
                @endif
            </div>
            @endif
        @else
            <div style="text-align:center;padding:60px 0;">
                <div style="font-size:4rem;margin-bottom:16px;">📝</div>
                <h3 style="margin-bottom:8px;">No blog posts found</h3>
                <p style="color:var(--clr-gray);">
                    @if(request('search'))
                        No results for "{{ request('search') }}". Try a different search term.
                    @else
                        Check back soon for new content!
                    @endif
                </p>
            </div>
        @endif
    </div>
</section>
@endsection
