@extends('layouts.admin')
@section('title', 'Create Diagnostic Test')
@section('actions')
    <a href="{{ route('admin.test-types.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.test-types.store') }}" method="POST" class="admin-form">
            @csrf
            @if($errors->any())<div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif

            <div class="form-row">
                <div class="form-group"><label>Test Name *</label><input type="text" name="name" class="form-control" required value="{{ old('name') }}"></div>
                <div class="form-group"><label>Category</label><input type="text" name="category" class="form-control" placeholder="Pathology, Imaging, etc." value="{{ old('category') }}"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Price (₹)</label><input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}"></div>
                <div class="form-group"><label>Duration (minutes)</label><input type="number" name="duration_minutes" min="5" max="1440" class="form-control" value="{{ old('duration_minutes') }}"></div>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="4" placeholder="Describe preparation, sample type, and turnaround time">{{ old('description') }}</textarea></div>
            <div class="form-group"><label>Status</label>
                <select name="is_active" class="form-control" style="max-width:220px;">
                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Visible to users</option>
                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Hidden from booking form</option>
                </select>
            </div>
            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-save"></i> Create Test</button>
        </form>
    </div>
</div>
@endsection