@extends('layouts.admin')
@section('title', isset($city) ? 'Edit City' : 'Create City')
@section('actions')
    <a href="{{ route('admin.cities.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ isset($city) ? route('admin.cities.update', $city) : route('admin.cities.store') }}"
              method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf
            @if(isset($city)) @method('PUT') @endif
            @if($errors->any())<div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif

            <div class="form-row">
                <div class="form-group"><label>City Name *</label><input type="text" name="name" class="form-control" required value="{{ old('name', $city->name ?? '') }}"></div>
                <div class="form-group"><label>Order</label><input type="number" name="order" class="form-control" value="{{ old('order', $city->order ?? 0) }}"></div>
            </div>
            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $city->slug ?? '') }}" placeholder="delhi-pet-care">
                <div class="form-hint">Leave blank to auto-generate from city name.</div>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $city->description ?? '') }}</textarea></div>

            <div class="panel" style="margin:12px 0 18px;">
                <div class="panel-header"><h2><i class="fas fa-search" style="margin-right:8px;color:var(--admin-primary);"></i> SEO Meta Tags</h2></div>
                <div class="panel-body">
                    <div class="form-group"><label>Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $city->meta_title ?? '') }}" placeholder="Pet Care Services in Delhi | PawCare"></div>
                    <div class="form-group"><label>Meta Description</label><textarea name="meta_description" class="form-control" rows="3" placeholder="Best pet care, grooming, and vaccination services in Delhi.">{{ old('meta_description', $city->meta_description ?? '') }}</textarea></div>
                    <div class="form-group"><label>Meta Keywords</label><input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $city->meta_keywords ?? '') }}" placeholder="pet care delhi, dog vaccination delhi, pet grooming delhi"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Image</label><input type="file" name="image" class="form-control" accept="image/*">
                    @if(isset($city) && $city->image)<img src="{{ $city->image_url }}" class="image-preview" style="width:120px;height:auto;margin-top:8px;">@endif
                </div>
                <div class="form-group"><label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $city->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $city->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-save"></i> {{ isset($city) ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</div>
@endsection
