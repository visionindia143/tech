@extends('layouts.admin')
@section('title', 'Add Nutrition Product')
@section('actions')
    <a href="{{ route('admin.nutrition-products.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.nutrition-products.store') }}" method="POST" class="admin-form" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
                <div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label>Pet Type *</label>
                    <select name="pet_type_id" class="form-control" required>
                        <option value="">Select Pet Type</option>
                        @foreach($petTypes as $petType)
                            <option value="{{ $petType->id }}" {{ (string) old('pet_type_id') === (string) $petType->id ? 'selected' : '' }}>
                                {{ $petType->icon }} {{ $petType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="e.g. Premium Puppy Food">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price (INR)</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price') }}" placeholder="e.g. 799">
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', 0) }}">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Product details, ingredients, age guidance, etc.">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Product Images</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                <small style="color:var(--admin-gray);display:block;margin-top:6px;">Select one or more images for this product.</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-check">
                        <input type="hidden" name="show_price" value="0">
                        <input type="checkbox" name="show_price" value="1" {{ old('show_price', 1) ? 'checked' : '' }}>
                        <span>Show price on frontend page</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span>Active product</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-save"></i> Save Product</button>
        </form>
    </div>
</div>
@endsection
