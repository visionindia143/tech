@extends('layouts.admin')
@section('title', 'Add Testimonial')
@section('actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn-admin btn-admin-outline">
        <i class="fas fa-arrow-left"></i> Back to Testimonials
    </a>
@endsection

@section('content')
<form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="admin-form">
    @csrf

    <div class="admin-sidebar-grid">

        {{-- Main Content --}}
        <div>
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-user-circle" style="margin-right:8px;color:var(--admin-primary);"></i> Customer Info</h2>
                </div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Customer Name *</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" placeholder="e.g. Priya Sharma" required>
                            @error('name')
                                <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Designation / Title</label>
                            <input type="text" name="designation" class="form-control"
                                   value="{{ old('designation') }}" placeholder="e.g. Pet Owner, Happy Customer">
                            @error('designation')
                                <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Customer Photo</label>
                        <input type="file" name="image" class="form-control" accept="image/*"
                               onchange="previewPhoto(this)">
                        <div class="form-hint">Optional · JPG, PNG · Max 2 MB</div>
                        @error('image')
                            <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                        @enderror
                        <div id="photoPreview" style="display:none;margin-top:12px;">
                            <img id="previewImg" src="" alt="Preview"
                                 style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--admin-border);">
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-comment-dots" style="margin-right:8px;color:var(--admin-primary);"></i> Testimonial Message</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Message *</label>
                        <textarea name="message" class="form-control" rows="5"
                                  placeholder="Enter the customer's feedback..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-star" style="margin-right:8px;color:#F59E0B;"></i> Rating & Settings</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Star Rating *</label>
                        <div style="display:flex;gap:8px;margin-bottom:8px;" id="starPicker">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" data-star="{{ $i }}"
                                        onclick="setRating({{ $i }})"
                                        style="background:none;border:none;cursor:pointer;font-size:1.8rem;padding:0;line-height:1;color:#D1D5DB;transition:color .15s;">
                                    <i class="fas fa-star"></i>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 5) }}" required>
                        <div style="font-size:0.82rem;color:var(--admin-gray);" id="ratingLabel">5 Stars</div>
                        @error('rating')
                            <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', 0) }}" min="0">
                        <div class="form-hint">Lower numbers appear first.</div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   style="width:18px;height:18px;accent-color:var(--admin-primary);">
                            <span style="font-weight:600;">Active (visible on frontend)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn-admin btn-admin-primary" style="flex:1;justify-content:center;">
                    <i class="fas fa-save"></i> Save Testimonial
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn-admin btn-admin-outline">Cancel</a>
            </div>
        </div>

    </div>
</form>
@endsection

@section('scripts')
<script>
function setRating(value) {
    document.getElementById('ratingInput').value = value;
    document.getElementById('ratingLabel').textContent = value + ' Star' + (value > 1 ? 's' : '');
    document.querySelectorAll('#starPicker button').forEach(function(btn) {
        const star = parseInt(btn.getAttribute('data-star'));
        btn.style.color = star <= value ? '#F59E0B' : '#D1D5DB';
    });
}

function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('photoPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Init star display
document.addEventListener('DOMContentLoaded', function() {
    setRating(parseInt(document.getElementById('ratingInput').value) || 5);
});
</script>
@endsection
