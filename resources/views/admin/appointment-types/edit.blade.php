@extends('layouts.admin')
@section('title', isset($appointment_type) ? 'Edit Appointment Type' : 'Create Appointment Type')
@section('actions')
    <a href="{{ route('admin.appointment-types.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ isset($appointment_type) ? route('admin.appointment-types.update', $appointment_type) : route('admin.appointment-types.store') }}"
              method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf
            @if(isset($appointment_type)) @method('PUT') @endif
            @if($errors->any())<div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif

            <div class="form-row">
                <div class="form-group"><label>Title *</label><input type="text" name="title" class="form-control" required value="{{ old('title', $appointment_type->title ?? '') }}"></div>
          </div>
          <div class="form-row">
                <div class="form-group"><label>Price (₹)</label><input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $appointment_type->price ?? '') }}"></div>
            </div>
           
            <div class="form-row">
                
                <div class="form-group"><label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $appointment_type->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $appointment_type->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-save"></i> {{ isset($appointment_type) ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</div>
@endsection
