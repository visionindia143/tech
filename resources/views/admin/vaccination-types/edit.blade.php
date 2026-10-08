@extends('layouts.admin')
@section('title', isset($vaccinationType) ? 'Edit Vaccination Type' : 'Create Vaccination Type')
@section('actions')
    <a href="{{ route('admin.vaccination-types.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ isset($vaccinationType) ? route('admin.vaccination-types.update', $vaccinationType) : route('admin.vaccination-types.store') }}"
              method="POST" class="admin-form">
            @csrf
            @if(isset($vaccinationType)) @method('PUT') @endif
            @if($errors->any())<div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif

            <div class="form-row">
                <div class="form-group"><label>Name *</label><input type="text" name="name" class="form-control" required value="{{ old('name', $vaccinationType->name ?? '') }}"></div>
                <div class="form-group"><label>Price (₹)</label><input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $vaccinationType->price ?? '') }}"></div>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $vaccinationType->description ?? '') }}</textarea></div>
            <div class="form-group"><label>Status</label>
                <select name="is_active" class="form-control" style="max-width:200px;">
                    <option value="1" {{ old('is_active', $vaccinationType->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $vaccinationType->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-save"></i> {{ isset($vaccinationType) ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</div>
@endsection
