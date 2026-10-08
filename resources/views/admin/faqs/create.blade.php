@extends('layouts.admin')
@section('title', 'Add FAQ')
@section('actions')
    <a href="{{ route('admin.faqs.index') }}" class="btn-admin btn-admin-outline">
        <i class="fas fa-arrow-left"></i> Back to FAQs
    </a>
@endsection

@section('content')
<form action="{{ route('admin.faqs.store') }}" method="POST" class="admin-form">
    @csrf

    <div class="admin-sidebar-grid">

        {{-- Main Content --}}
        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-question-circle" style="margin-right:8px;color:var(--admin-primary);"></i> FAQ Content</h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Question *</label>
                    <input type="text" name="question" class="form-control" value="{{ old('question') }}"
                           placeholder="e.g. How do I schedule a vaccination?" required>
                    @error('question')
                        <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Answer *</label>
                    <textarea name="answer" class="form-control" rows="6"
                              placeholder="Provide a clear and helpful answer..." required>{{ old('answer') }}</textarea>
                    @error('answer')
                        <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
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
                        <div class="form-hint">Lower numbers appear first.</div>
                        @error('sort_order')
                            <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   style="width:18px;height:18px;accent-color:var(--admin-primary);">
                            <span style="font-weight:600;">Active (visible on frontend)</span>
                        </label>
                        @error('is_active')
                            <div style="color:#EF4444;font-size:0.82rem;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn-admin btn-admin-primary" style="flex:1;justify-content:center;">
                    <i class="fas fa-save"></i> Save FAQ
                </button>
                <a href="{{ route('admin.faqs.index') }}" class="btn-admin btn-admin-outline">
                    Cancel
                </a>
            </div>
        </div>

    </div>
</form>
@endsection
