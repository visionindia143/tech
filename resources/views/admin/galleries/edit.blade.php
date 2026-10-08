@extends('layouts.admin')
@section('title', isset($gallery) ? 'Edit Gallery Item' : 'Add Gallery Item')
@section('actions')
    <a href="{{ route('admin.galleries.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ isset($gallery) ? route('admin.galleries.update', $gallery) : route('admin.galleries.store') }}"
              method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf
            @if(isset($gallery)) @method('PUT') @endif
            @if($errors->any())<div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif

            <div class="form-row">
                <div class="form-group"><label>Title *</label><input type="text" name="title" class="form-control" required value="{{ old('title', $gallery->title ?? '') }}"></div>
                <div class="form-group"><label>Pet Type</label><input type="text" name="pet_type" class="form-control" value="{{ old('pet_type', $gallery->pet_type ?? '') }}" placeholder="Dog, Cat, etc."></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Breed</label><input type="text" name="breed" class="form-control" value="{{ old('breed', $gallery->breed ?? '') }}"></div>
                <div class="form-group"><label>Order</label><input type="number" name="order" class="form-control" value="{{ old('order', $gallery->order ?? 0) }}"></div>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $gallery->description ?? '') }}</textarea></div>
            <div class="form-row">
                <div class="form-group">
                    <label>Image {{ isset($gallery) ? '' : '*' }}</label>
                    <input type="file" name="image" class="form-control" accept="image/*" {{ isset($gallery) ? '' : 'required' }}>
                    @if(isset($gallery) && $gallery->image)<img src="{{ $gallery->image_url }}" class="image-preview" style="width:120px;height:auto;margin-top:8px;">@endif
                </div>
                <div class="form-group"><label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $gallery->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $gallery->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="show_on_home" value="1" {{ old('show_on_home', $gallery->show_on_home ?? true) ? 'checked' : '' }}>
                    <span>Show on homepage gallery</span>
                </label>
                <p class="form-hint">Disable this if you want to keep the image in admin only.</p>
            </div>
            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-save"></i> {{ isset($gallery) ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</div>
@endsection
