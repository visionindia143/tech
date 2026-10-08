@extends('layouts.admin')
@section('title', isset($page) ? 'Edit Page' : 'Create Page')
@section('actions')
    <a href="{{ route('admin.pages.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
      method="POST" enctype="multipart/form-data" class="admin-form">
    @csrf
    @if(isset($page)) @method('PUT') @endif
    @if($errors->any())<div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div>
            <div class="panel">
                <div class="panel-header"><h2>Page Content</h2></div>
                <div class="panel-body">
                    <div class="form-group"><label>Title *</label><input type="text" name="title" class="form-control" required value="{{ old('title', $page->title ?? '') }}"></div>
                    <div class="form-group"><label>Content</label><textarea name="content" class="form-control" id="content" rows="15" style="min-height:300px;">{{ old('content', $page->content ?? '') }}</textarea></div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header"><h2><i class="fas fa-search" style="margin-right:8px;color:var(--admin-primary);"></i> SEO</h2></div>
                <div class="panel-body">
                    <div class="form-group"><label>Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title ?? '') }}"></div>
                    <div class="form-group"><label>Meta Description</label><textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $page->meta_description ?? '') }}</textarea></div>
                    <div class="form-group"><label>Meta Keywords</label><input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}"></div>
                </div>
            </div>
        </div>
        <div>
            <div class="panel">
                <div class="panel-header"><h2>Publish</h2></div>
                <div class="panel-body">
                    <div class="form-group"><label>Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ old('is_active', $page->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $page->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-admin btn-admin-primary" style="width:100%;justify-content:center;"><i class="fas fa-save"></i> {{ isset($page) ? 'Update' : 'Create' }}</button>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header"><h2>Featured Image</h2></div>
                <div class="panel-body">
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if(isset($page) && $page->image)<img src="{{ $page->image_url }}" class="image-preview" style="width:100%;height:auto;margin-top:8px;">@endif
                </div>
            </div>
            <div class="panel">
                <div class="panel-header"><h2><i class="fab fa-youtube" style="margin-right:8px;color:#FF0000;"></i> YouTube Video</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>YouTube Embed URL</label>
                        <input type="url" name="youtube_video_url" class="form-control"
                               value="{{ old('youtube_video_url', $page->youtube_video_url ?? '') }}"
                               placeholder="https://www.youtube.com/embed/VIDEO_ID">
                        <small style="color:var(--admin-gray);display:block;margin-top:6px;">
                            Paste the <strong>embed</strong> URL.<br>
                            Example: <code>https://www.youtube.com/embed/dQw4w9WgXcQ</code><br>
                            From YouTube: Share &rarr; Embed &rarr; copy the <code>src</code> URL.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
@php($tinyApiKey = rawurlencode($adminSettings['tinymce_api_key']))
<script src="https://cdn.tiny.cloud/1/{{ $tinyApiKey }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#content', height: 400, menubar: true,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: "body { font-family: 'Outfit', sans-serif; font-size: 15px; }"
        });
    }
</script>
@endsection
