@extends('layouts.admin')
@section('title', isset($blog) ? 'Edit Blog' : 'Create Blog')

@section('actions')
    <a href="{{ route('admin.blogs.index') }}" class="btn-admin btn-admin-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
@endsection

@section('content')
<form action="{{ isset($blog) ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}"
      method="POST" enctype="multipart/form-data" class="admin-form">
    @csrf
    @if(isset($blog)) @method('PUT') @endif

    @if($errors->any())
        <div class="admin-alert admin-alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        {{-- Main Content --}}
        <div>
            <div class="panel">
                <div class="panel-header"><h2>Blog Content</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" class="form-control" required
                               value="{{ old('title', $blog->title ?? '') }}"
                               placeholder="Enter blog title">
                    </div>

                    <div class="form-group">
                        <label>Content *</label>
                        <textarea name="content" class="form-control" id="content" rows="15"
                                  style="min-height:300px;">{{ old('content', $blog->content ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SEO Settings --}}
            <div class="panel">
                <div class="panel-header"><h2><i class="fas fa-search" style="margin-right:8px;color:var(--admin-primary);"></i> SEO Settings</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" class="form-control"
                               value="{{ old('meta_title', $blog->meta_title ?? '') }}"
                               placeholder="SEO title (max 60 characters)">
                        <p class="form-hint">Leave empty to use blog title</p>
                    </div>

                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3"
                                  placeholder="SEO description (max 160 characters)">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control"
                               value="{{ old('meta_keywords', $blog->meta_keywords ?? '') }}"
                               placeholder="keyword1, keyword2, keyword3">
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="panel">
                <div class="panel-header"><h2>Publish</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="draft" {{ old('status', $blog->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="publish" {{ old('status', $blog->status ?? '') === 'publish' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="show_in_slider" value="1" {{ old('show_in_slider', $blog->show_in_slider ?? false) ? 'checked' : '' }}>
                            <span>Show in homepage blog slider</span>
                        </label>
                        <p class="form-hint">Only published blogs with this enabled will appear in the homepage slider.</p>
                    </div>

                    <button type="submit" class="btn-admin btn-admin-primary" style="width:100%;justify-content:center;">
                        <i class="fas fa-save"></i>
                        {{ isset($blog) ? 'Update Blog' : 'Create Blog' }}
                    </button>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h2>Featured Image</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <p class="form-hint">JPG, PNG, WEBP. Max 2MB.</p>
                    </div>
                    @if(isset($blog) && $blog->image)
                        <img src="{{ $blog->image_url }}" alt="" class="image-preview" style="width:100%;height:auto;">
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
{{-- TinyMCE CDN --}}
@php($tinyApiKey = rawurlencode($adminSettings['tinymce_api_key']))
<script src="https://cdn.tiny.cloud/1/{{ $tinyApiKey }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Initialize TinyMCE if available, otherwise fallback to plain textarea
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#content',
            height: 400,
            menubar: true,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: "body { font-family: 'Outfit', sans-serif; font-size: 15px; }"
        });
    }
</script>
@endsection
