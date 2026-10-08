@extends('layouts.admin')
@section('title', isset($petType) ? 'Edit Pet Type' : 'Create Pet Type')
@section('actions')
    <a href="{{ route('admin.pet-types.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<div class="panel" style="max-width:560px;">
    <div class="panel-body">
        <form action="{{ isset($petType) ? route('admin.pet-types.update', $petType) : route('admin.pet-types.store') }}"
              method="POST" class="admin-form">
            @csrf
            @if(isset($petType)) @method('PUT') @endif

            @if($errors->any())
                <div class="admin-alert admin-alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <div class="form-row">
                <div class="form-group" style="flex:2;">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control" required
                           value="{{ old('name', $petType->name ?? '') }}"
                           placeholder="e.g. Dog, Cat, Bird">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Icon (emoji)</label>
                    <input type="text" name="icon" class="form-control"
                           value="{{ old('icon', $petType->icon ?? '') }}"
                           placeholder="🐕"
                           style="font-size:1.4rem;text-align:center;">
                    <small style="color:var(--admin-gray);">Paste any emoji</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="flex:1;">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0"
                           value="{{ old('sort_order', $petType->sort_order ?? 0) }}"
                           placeholder="0">
                    <small style="color:var(--admin-gray);">Lower number = shown first</small>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $petType->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $petType->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary">
                <i class="fas fa-save"></i>
                {{ isset($petType) ? 'Update Pet Type' : 'Create Pet Type' }}
            </button>
        </form>
    </div>
</div>
@endsection
