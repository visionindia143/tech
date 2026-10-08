@extends('layouts.frontend')

@section('meta_title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>{{ $page->title }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> / {{ $page->title }}
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="blog-detail fade-up">
            @if($page->image)
                <img src="{{ $page->image_url }}" alt="{{ $page->title }}" class="blog-detail-img">
            @endif
            <div class="blog-content">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</section>
@endsection
