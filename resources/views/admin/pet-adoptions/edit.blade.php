@extends('layouts.admin')
@section('title', 'Edit Pet: ' . $petAdoption->name)
@section('actions')
    <a href="{{ route('admin.pet-adoptions.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<form action="{{ route('admin.pet-adoptions.update', $petAdoption) }}" method="POST" enctype="multipart/form-data" class="admin-form">
    @csrf @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
        {{-- Left --}}
        <div>
            <div class="panel">
                <div class="panel-header"><h2><i class="fas fa-paw" style="margin-right:8px;color:var(--admin-primary);"></i> Basic Info</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Pet Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $petAdoption->name) }}" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Pet Type *</label>
                            <select name="pet_type" class="form-control" required>
                                @foreach(['dog','cat','rabbit','bird','hamster','fish','other'] as $t)
                                    <option value="{{ $t }}" {{ old('pet_type', $petAdoption->pet_type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Gender *</label>
                            <select name="gender" class="form-control" required>
                                <option value="male"    {{ old('gender', $petAdoption->gender)=='male'    ? 'selected' : '' }}>Male</option>
                                <option value="female"  {{ old('gender', $petAdoption->gender)=='female'  ? 'selected' : '' }}>Female</option>
                                <option value="unknown" {{ old('gender', $petAdoption->gender)=='unknown' ? 'selected' : '' }}>Unknown</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Breed</label>
                            <input type="text" name="breed" class="form-control" value="{{ old('breed', $petAdoption->breed) }}">
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control" value="{{ old('age', $petAdoption->age) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Size</label>
                            <select name="size" class="form-control">
                                <option value="">Select size</option>
                                @foreach(['small','medium','large'] as $s)
                                    <option value="{{ $s }}" {{ old('size', $petAdoption->size)==$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" name="color" class="form-control" value="{{ old('color', $petAdoption->color) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $petAdoption->short_description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Full Description</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description', $petAdoption->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div>
            <div class="panel">
                <div class="panel-header"><h2><i class="fas fa-camera" style="margin-right:8px;color:var(--admin-primary);"></i> Photos</h2></div>
                <div class="panel-body">
                    {{-- Existing images --}}
                    @if(count($petAdoption->images ?? []))
                    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
                        @foreach($petAdoption->images as $img)
                        <div style="position:relative;">
                            <img src="{{ asset('uploads/adoptions/'.$img) }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid var(--admin-border);">
                            <label style="position:absolute;top:-4px;right:-4px;background:#EF4444;color:white;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:0.7rem;">
                                <input type="checkbox" name="remove_images[]" value="{{ $img }}" style="display:none;">×
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <p style="font-size:0.78rem;color:var(--admin-gray);margin-bottom:12px;">Click × on an image to remove it on save.</p>
                    @endif
                    <div class="form-group">
                        <label>Add More Images</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top:16px;">
                <div class="panel-header"><h2><i class="fas fa-cog" style="margin-right:8px;color:var(--admin-primary);"></i> Settings</h2></div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Adoption Fee (₹)</label>
                            <input type="number" name="adoption_fee" class="form-control" value="{{ old('adoption_fee', $petAdoption->adoption_fee) }}" min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" class="form-control">
                                @foreach(['available','reserved','adopted'] as $s)
                                    <option value="{{ $s }}" {{ old('status', $petAdoption->status)==$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $petAdoption->order) }}" min="0">
                    </div>
                    <div style="display:flex;flex-direction:column;gap:12px;margin-top:8px;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:500;">
                            <input type="checkbox" name="is_vaccinated" value="1" {{ $petAdoption->is_vaccinated ? 'checked' : '' }}> Vaccinated
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:500;">
                            <input type="checkbox" name="is_neutered" value="1" {{ $petAdoption->is_neutered ? 'checked' : '' }}> Neutered / Spayed
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:500;">
                            <input type="checkbox" name="is_featured" value="1" {{ $petAdoption->is_featured ? 'checked' : '' }}> Featured
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:500;">
                            <input type="checkbox" name="is_active" value="1" {{ $petAdoption->is_active ? 'checked' : '' }}> Active
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
            <i class="fas fa-save"></i> Update Pet
        </button>
    </div>
</form>

<script>
// Toggle image removal checkbox UI
document.querySelectorAll('input[name="remove_images[]"]').forEach(cb => {
    cb.addEventListener('change', function() {
        const wrapper = this.closest('div[style*="position:relative"]');
        if (this.checked) {
            wrapper.style.opacity = '0.4';
        } else {
            wrapper.style.opacity = '1';
        }
    });
});
</script>
@endsection
