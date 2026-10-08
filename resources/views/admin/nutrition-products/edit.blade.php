@extends('layouts.admin')
@section('title', 'Edit Nutrition Product')
@section('actions')
    <a href="{{ route('admin.nutrition-products.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.nutrition-products.update', $product) }}" method="POST" class="admin-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label>Pet Type *</label>
                    <select name="pet_type_id" class="form-control" required>
                        <option value="">Select Pet Type</option>
                        @foreach($petTypes as $petType)
                            <option value="{{ $petType->id }}" {{ (string) old('pet_type_id', $product->pet_type_id) === (string) $petType->id ? 'selected' : '' }}>
                                {{ $petType->icon }} {{ $petType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $product->name) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price (INR)</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price', $product->price) }}">
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $product->sort_order) }}">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Add More Images</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                <small style="color:var(--admin-gray);display:block;margin-top:6px;">New images will be added to the existing gallery.</small>
            </div>

            @if(!empty($product->images))
                <div class="form-group">
                    <label>Current Images</label>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px;">
                        @foreach($product->image_urls as $index => $imageUrl)
                            <label style="display:block;border:1px solid var(--admin-border);border-radius:12px;padding:8px;">
                                <img src="{{ $imageUrl }}" alt="Product image" style="width:100%;height:110px;object-fit:cover;border-radius:8px;margin-bottom:8px;">
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <input type="checkbox" name="remove_images[]" value="{{ $product->images[$index] }}">
                                    <small>Remove</small>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label class="form-check">
                        <input type="hidden" name="show_price" value="0">
                        <input type="checkbox" name="show_price" value="1" {{ old('show_price', $product->show_price) ? 'checked' : '' }}>
                        <span>Show price on frontend page</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <span>Active product</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-save"></i> Update Product</button>
        </form>
    </div>
</div>
@endsection
