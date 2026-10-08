@extends('layouts.admin')
@section('title', 'Add Animal Type')
@section('actions')
    <a href="{{ route('admin.animal-types.index') }}" class="btn-admin btn-admin-outline">
        <i class="fas fa-arrow-left"></i> Back to Animal Types
    </a>
@endsection

@section('content')
<form action="{{ route('admin.animal-types.store') }}" method="POST" class="admin-form">
    @csrf

    <div class="admin-sidebar-grid">

        {{-- Main Form --}}
        <div>
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-paw" style="margin-right:8px;color:var(--admin-primary);"></i> Animal Details</h2>
                </div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Animal Name *</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" placeholder="e.g. Dog, Cat, Bird" required>
                            @error('name')
                                <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Font Awesome Icon Class *</label>
                            <input type="text" name="icon_class" id="icon_class" class="form-control"
                                   value="{{ old('icon_class') }}" placeholder="fas fa-dog" required>
                            <div class="form-hint">e.g. <code>fas fa-dog</code>, <code>fas fa-cat</code></div>
                            @error('icon_class')
                                <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Icon Preview --}}
                    <div style="background:var(--admin-light-gray,#F8F9FA);border-radius:12px;padding:24px;text-align:center;">
                        <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,rgba(var(--admin-primary-rgb,255,107,53),0.15),rgba(var(--admin-primary-rgb,255,107,53),0.05));display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i id="iconPreview" class="fas fa-question-circle" style="font-size:2.4rem;color:var(--admin-primary);"></i>
                        </div>
                        <div style="font-size:0.82rem;color:var(--admin-gray);">Icon preview — updates as you type</div>
                    </div>
                </div>
            </div>

            {{-- Quick Pick Icons --}}
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-icons" style="margin-right:8px;color:var(--admin-primary);"></i> Quick Pick Icons</h2>
                </div>
                <div class="panel-body">
                    <p style="color:var(--admin-gray);font-size:0.85rem;margin-bottom:16px;">Click any icon below to use it instantly.</p>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;">
                        @php
                        $iconOptions = [
                            'fas fa-dog'        => 'Dog',
                            'fas fa-cat'        => 'Cat',
                            'fas fa-dove'       => 'Bird',
                            'fas fa-fish'       => 'Fish',
                            'fas fa-horse'      => 'Horse',
                            'fas fa-paw'        => 'Paw',
                            'fas fa-frog'       => 'Frog',
                            'fas fa-kiwi-bird'  => 'Kiwi',
                            'fas fa-spider'     => 'Spider',
                            'fas fa-hippo'      => 'Hippo',
                            'fas fa-otter'      => 'Otter',
                            'fas fa-crow'       => 'Crow',
                        ];
                        @endphp
                        @foreach($iconOptions as $icon => $label)
                        <button type="button" class="icon-option"
                                data-icon="{{ $icon }}"
                                onclick="pickIcon('{{ $icon }}')"
                                style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px 8px;border:2px solid var(--admin-border);border-radius:10px;background:white;cursor:pointer;transition:all .15s;font-size:0.8rem;color:var(--admin-gray);">
                            <i class="{{ $icon }}" style="font-size:1.5rem;color:var(--admin-primary);"></i>
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-sliders-h" style="margin-right:8px;color:var(--admin-primary);"></i> Settings</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', 0) }}" min="0">
                        <div class="form-hint">Lower numbers appear first in the carousel.</div>
                        @error('sort_order')
                            <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   style="width:18px;height:18px;accent-color:var(--admin-primary);">
                            <span style="font-weight:600;">Show in carousel (active)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn-admin btn-admin-primary" style="flex:1;justify-content:center;">
                    <i class="fas fa-save"></i> Save
                </button>
                <a href="{{ route('admin.animal-types.index') }}" class="btn-admin btn-admin-outline">Cancel</a>
            </div>
        </div>

    </div>
</form>
@endsection

@section('scripts')
<script>
const iconInput   = document.getElementById('icon_class');
const iconPreview = document.getElementById('iconPreview');

function pickIcon(iconClass) {
    iconInput.value = iconClass;
    updatePreview(iconClass);
    document.querySelectorAll('.icon-option').forEach(function(btn) {
        btn.style.borderColor   = btn.getAttribute('data-icon') === iconClass ? 'var(--admin-primary)' : 'var(--admin-border)';
        btn.style.background    = btn.getAttribute('data-icon') === iconClass ? 'rgba(var(--admin-primary-rgb,255,107,53),0.06)' : 'white';
    });
}

function updatePreview(iconClass) {
    iconPreview.className = iconClass || 'fas fa-question-circle';
}

iconInput.addEventListener('input', function() {
    updatePreview(this.value.trim());
    document.querySelectorAll('.icon-option').forEach(function(btn) {
        btn.style.borderColor = 'var(--admin-border)';
        btn.style.background  = 'white';
    });
});

// Init
if (iconInput.value) updatePreview(iconInput.value);
</script>
@endsection
