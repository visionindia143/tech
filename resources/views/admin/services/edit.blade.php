@extends('layouts.admin')
@section('title', isset($service) ? 'Edit Service' : 'Create Service')
@section('actions')
    <a href="{{ route('admin.services.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}"
              method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf
            @if(isset($service)) @method('PUT') @endif
            @if($errors->any())<div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif

            <div class="form-row">
                <div class="form-group"><label>Title *</label><input type="text" name="title" class="form-control" required value="{{ old('title', $service->title ?? '') }}"></div>
                <div class="form-group"><label>Icon Class</label><input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon ?? '') }}" placeholder="fas fa-syringe"></div>
            </div>
            <div class="form-group"><label>Short Description</label><textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $service->short_description ?? '') }}</textarea></div>
            <div class="form-group"><label>Full Description</label><textarea name="description" id="description" class="form-control" rows="5">{{ old('description', $service->description ?? '') }}</textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Price (₹)</label><input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $service->price ?? '') }}"></div>
                <div class="form-group"><label>Order</label><input type="number" name="order" class="form-control" value="{{ old('order', $service->order ?? 0) }}"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Button Label</label><input type="text" name="button_label" class="form-control"  value="{{ old('button_label', $service->button_label ?? '') }}"></div>
                <div class="form-group"><label>Button Url</label><input type="text" name="button_url" class="form-control" value="{{ old('button_url', $service->button_url ?? '') }}" placeholder="/home"></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Image</label><input type="file" name="image" class="form-control" accept="image/*">
                    @if(isset($service) && $service->image)<img src="{{ $service->image_url }}" class="image-preview" style="width:120px;height:auto;margin-top:8px;">@endif
                </div>
                <div class="form-group"><label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $service->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $service->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-save"></i> {{ isset($service) ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</div>
@endsection
@section('scripts')
{{-- TinyMCE CDN --}}
@section('scripts')
{{-- TinyMCE CDN --}}
@php($tinyApiKey = rawurlencode($adminSettings['tinymce_api_key']))
<script src="https://cdn.tiny.cloud/1/{{ $tinyApiKey }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Initialize TinyMCE if available, otherwise fallback to plain textarea
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#description',
            height: 400,
            menubar: true,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: "body { font-family: 'Outfit', sans-serif; font-size: 15px; }"
        });
    }
</script>
@endsection
