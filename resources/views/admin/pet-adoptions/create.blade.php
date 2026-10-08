@extends('layouts.admin')
@section('title', 'Add Pet for Adoption')
@section('actions')
    <a href="{{ route('admin.pet-adoptions.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<form action="{{ route('admin.pet-adoptions.store') }}" method="POST" enctype="multipart/form-data" class="admin-form">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
        {{-- Left Column --}}
        <div>
            <div class="panel">
                <div class="panel-header"><h2><i class="fas fa-paw" style="margin-right:8px;color:var(--admin-primary);"></i> Basic Info</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Pet Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Pet Type *</label>
                            <select name="pet_type" class="form-control" required>
                                @foreach(['dog','cat','rabbit','bird','hamster','fish','other'] as $t)
                                    <option value="{{ $t }}" {{ old('pet_type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Gender *</label>
                            <select name="gender" class="form-control" required>
                                <option value="male"    {{ old('gender')=='male'    ? 'selected' : '' }}>Male</option>
                                <option value="female"  {{ old('gender')=='female'  ? 'selected' : '' }}>Female</option>
                                <option value="unknown" {{ old('gender')=='unknown' ? 'selected' : '' }}>Unknown</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Breed</label>
                            <input type="text" name="breed" class="form-control" value="{{ old('breed') }}" placeholder="e.g. Labrador">
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control" value="{{ old('age') }}" placeholder="e.g. 2 years">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Size</label>
                            <select name="size" class="form-control">
                                <option value="">Select size</option>
                                <option value="small"   {{ old('size')=='small'  ? 'selected' : '' }}>Small</option>
                                <option value="medium"  {{ old('size')=='medium' ? 'selected' : '' }}>Medium</option>
                                <option value="large"   {{ old('size')=='large'  ? 'selected' : '' }}>Large</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" name="color" class="form-control" value="{{ old('color') }}" placeholder="e.g. Golden Brown">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2" placeholder="Brief tagline about this pet">{{ old('short_description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Full Description</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Detailed description, personality, history...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            <div class="panel">
                <div class="panel-header"><h2><i class="fas fa-camera" style="margin-right:8px;color:var(--admin-primary);"></i> Photos</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Upload Images (multiple allowed)</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <div class="form-hint">First image will be used as the main photo. Max 2MB each.</div>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top:16px;">
                <div class="panel-header"><h2><i class="fas fa-cog" style="margin-right:8px;color:var(--admin-primary);"></i> Settings</h2></div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Adoption Fee (₹)</label>
                            <input type="number" name="adoption_fee" class="form-control" value="{{ old('adoption_fee') }}" placeholder="0 = Free" min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" class="form-control">
                                <option value="available" selected>Available</option>
                                <option value="reserved">Reserved</option>
                                <option value="adopted">Adopted</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Display Order</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" min="0">
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:12px;margin-top:8px;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:500;">
                            <input type="checkbox" name="is_vaccinated" value="1" {{ old('is_vaccinated') ? 'checked' : '' }}>
                            Vaccinated
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:500;">
                            <input type="checkbox" name="is_neutered" value="1" {{ old('is_neutered') ? 'checked' : '' }}>
                            Neutered / Spayed
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:500;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            Featured (show badge)
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:500;">
                            <input type="checkbox" name="is_active" value="1" checked>
                            Active (visible on site)
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div style="background:#FEF2F2;color:#991B1B;padding:16px;border-radius:8px;margin-top:16px;">
            <ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div style="margin-top:24px;">
        <button type="submit" class="btn-admin btn-admin-primary" style="padding:12px 36px;">
            <i class="fas fa-save"></i> Save Pet
        </button>
    </div>
</form>
@endsection
