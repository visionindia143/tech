@extends('layouts.admin')
@section('title', isset($founder) ? 'Edit Founder' : 'Add Founder')
@section('actions')
    <a href="{{ route('admin.founders.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')

<form action="{{ isset($founder) ? route('admin.founders.update', $founder) : route('admin.founders.store') }}"
      method="POST" enctype="multipart/form-data" class="admin-form">
    @csrf
    @if(isset($founder)) @method('PUT') @endif
    @if($errors->any())
        <div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div>
            <div class="panel">
                <div class="panel-header"><h2>Founder Details</h2></div>
                <div class="panel-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="name" class="form-control" required
                                   value="{{ old('name', $founder->name ?? '') }}" placeholder="e.g. Rahul Suthar">
                        </div>
                        <div class="form-group">
                            <label>Role / Title *</label>
                            <input type="text" name="role" class="form-control" required
                                   value="{{ old('role', $founder->role ?? '') }}" placeholder="e.g. Co-Founder & CEO">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Bio / Story</label>
                        <textarea name="bio" class="form-control" rows="6"
                                  placeholder="Share their journey, passion for pets, background and vision...">{{ old('bio', $founder->bio ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h2><i class="fas fa-share-alt" style="margin-right:8px;color:var(--admin-primary);"></i> Social Links</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label><i class="fab fa-linkedin" style="color:#0A66C2;margin-right:6px;"></i> LinkedIn URL</label>
                        <input type="url" name="linkedin_url" class="form-control"
                               value="{{ old('linkedin_url', $founder->linkedin_url ?? '') }}"
                               placeholder="https://linkedin.com/in/username">
                    </div>
                    <div class="form-group">
                        <label><i class="fab fa-twitter" style="color:#1DA1F2;margin-right:6px;"></i> Twitter / X URL</label>
                        <input type="url" name="twitter_url" class="form-control"
                               value="{{ old('twitter_url', $founder->twitter_url ?? '') }}"
                               placeholder="https://twitter.com/username">
                    </div>
                    <div class="form-group">
                        <label><i class="fab fa-instagram" style="color:#E1306C;margin-right:6px;"></i> Instagram URL</label>
                        <input type="url" name="instagram_url" class="form-control"
                               value="{{ old('instagram_url', $founder->instagram_url ?? '') }}"
                               placeholder="https://instagram.com/username">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="panel">
                <div class="panel-header"><h2>Publish</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ old('is_active', $founder->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $founder->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" min="0"
                               value="{{ old('sort_order', $founder->sort_order ?? 0) }}"
                               placeholder="0 = first">
                        <small style="color:var(--admin-gray);">Lower number appears first</small>
                    </div>
                    <button type="submit" class="btn-admin btn-admin-primary" style="width:100%;justify-content:center;">
                        <i class="fas fa-save"></i> {{ isset($founder) ? 'Update Founder' : 'Add Founder' }}
                    </button>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h2>Photo</h2></div>
                <div class="panel-body">
                    @if(isset($founder) && $founder->image)
                        <img src="{{ $founder->image_url }}" alt="{{ $founder->name }}"
                             style="width:100%;border-radius:12px;object-fit:cover;max-height:200px;margin-bottom:12px;">
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small style="color:var(--admin-gray);">Recommended: square photo, min 400×400px. JPG/PNG/WebP, max 4MB.</small>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
