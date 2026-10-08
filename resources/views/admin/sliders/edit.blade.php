@extends('layouts.admin')
@section('title', isset($slider) ? 'Edit Slider' : 'Create Slider')
@section('actions')
    <a href="{{ route('admin.sliders.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ isset($slider) ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}"
              method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf
            @if(isset($slider)) @method('PUT') @endif

            @if($errors->any())
                <div class="admin-alert admin-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title', $slider->title ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Order</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', $slider->order ?? 0) }}">
                </div>
            </div>

            <div class="form-group">
                <label>Subtitle</label>
                <textarea name="subtitle" class="form-control" rows="2">{{ old('subtitle', $slider->subtitle ?? '') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Button Text</label>
                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $slider->button_text ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Button Link</label>
                    <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $slider->button_link ?? '') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Desktop Image {{ isset($slider) ? '' : '*' }}</label>
                    <small class="text-muted" style="display:block;margin-bottom:6px;">PC / Laptop ke liye background image (recommended: 1920×600px)</small>
                    <input type="file" name="image" class="form-control" accept="image/*" {{ isset($slider) ? '' : 'required' }}
                           onchange="previewImage(this, 'preview_desktop')">
                    @if(isset($slider) && $slider->image)
                        <img id="preview_desktop" src="{{ $slider->image_url }}" alt="" class="image-preview" style="width:100%;max-width:320px;height:auto;margin-top:10px;border-radius:6px;border:1px solid #ddd;">
                    @else
                        <img id="preview_desktop" src="" alt="" class="image-preview" style="display:none;width:100%;max-width:320px;height:auto;margin-top:10px;border-radius:6px;border:1px solid #ddd;">
                    @endif
                </div>
                <div class="form-group">
                    <label>Mobile Image <span style="color:#888;font-weight:400;">(Optional)</span></label>
                    <small class="text-muted" style="display:block;margin-bottom:6px;">Mobile / Tablet ke liye alag image (recommended: 768×900px). Nahi doge to desktop image use hogi.</small>
                    <input type="file" name="mobile_image" class="form-control" accept="image/*"
                           onchange="previewImage(this, 'preview_mobile')">
                    @if(isset($slider) && $slider->mobile_image)
                        <div id="mobile-img-wrap" style="position:relative;display:inline-block;margin-top:10px;">
                            <img id="preview_mobile" src="{{ $slider->mobile_image_url }}" alt="" style="width:160px;height:auto;border-radius:6px;border:1px solid #ddd;display:block;">
                            <button type="button" id="remove-mobile-btn"
                                onclick="removeMobileImage()"
                                title="Remove mobile image"
                                style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;border-radius:50%;background:#e74c3c;border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:11px;line-height:1;box-shadow:0 2px 6px rgba(0,0,0,.2);">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="hidden" name="remove_mobile_image" id="remove_mobile_image_input" value="0">
                    @else
                        <img id="preview_mobile" src="" alt="" style="display:none;width:160px;height:auto;margin-top:10px;border-radius:6px;border:1px solid #ddd;">
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $slider->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $slider->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-save"></i> {{ isset($slider) ? 'Update' : 'Create' }} Slider</button>
        </form>
    </div>
</div>

@section('scripts')
<script>
function previewImage(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function removeMobileImage() {
    document.getElementById('mobile-img-wrap').style.display = 'none';
    document.getElementById('remove_mobile_image_input').value = '1';
    // file input clear
    var fi = document.querySelector('input[name="mobile_image"]');
    if (fi) fi.value = '';
}
</script>
@endsection
@endsection
