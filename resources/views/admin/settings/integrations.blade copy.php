@extends('layouts.admin')
@section('title', 'API & Integrations')

@section('content')
<form action="{{ route('admin.integrations.update') }}" method="POST" class="admin-form">
    @csrf
    @method('PUT')

    @if($errors->any())
        <div class="admin-alert admin-alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="panel">
        <div class="panel-header">
            <h2><i class="fas fa-key" style="margin-right:8px;color:var(--admin-primary);"></i> Tiny Editor</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>TinyMCE API Key</label>
                <input
                    type="text"
                    name="tinymce_api_key"
                    class="form-control"
                    value="{{ old('tinymce_api_key', $settings['tinymce_api_key'] ?? '') }}"
                    placeholder="Paste your TinyMCE API key">
                <div class="form-hint">This key will be used automatically in blog/page editor screens.</div>
            </div>

            <div class="form-group">
                <label>Tiny Drive API Key (Optional)</label>
                <input
                    type="text"
                    name="tiny_drive_api_key"
                    class="form-control"
                    value="{{ old('tiny_drive_api_key', $settings['tiny_drive_api_key'] ?? '') }}"
                    placeholder="Optional Tiny Drive key">
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2><i class="fas fa-puzzle-piece" style="margin-right:8px;color:var(--admin-primary);"></i> Other Extra API/Config</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Extra API Settings</label>
                <textarea
                    name="extra_api_settings"
                    class="form-control"
                    rows="8"
                    placeholder="You can store any additional API keys, notes, JSON snippets, or integration config here.">{{ old('extra_api_settings', $settings['extra_api_settings'] ?? '') }}</textarea>
                <div class="form-hint">Tip: For multiple entries, keep one key/value per line.</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2><i class="fas fa-shield-alt" style="margin-right:8px;color:var(--admin-primary);"></i> Google reCAPTCHA v2</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="recaptcha_enabled" value="1"
                        {{ ($settings['recaptcha_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                        style="width:18px;height:18px;cursor:pointer;">
                    <span>Enable reCAPTCHA on Contact / Adoption / Vaccination forms</span>
                </label>
                <div class="form-hint">When enabled, a "I'm not a robot" checkbox will appear on all public forms to block bots.</div>
            </div>
            <div class="form-group">
                <label>Site Key</label>
                <input
                    type="text"
                    name="recaptcha_site_key"
                    class="form-control"
                    value="{{ old('recaptcha_site_key', $settings['recaptcha_site_key'] ?? '') }}"
                    placeholder="6Le...">
                <div class="form-hint">Shown to users — used in the widget. Get keys at <a href="https://www.google.com/recaptcha/admin/create" target="_blank" rel="noopener">google.com/recaptcha</a>.</div>
            </div>
            <div class="form-group">
                <label>Secret Key</label>
                <input
                    type="text"
                    name="recaptcha_secret_key"
                    class="form-control"
                    value="{{ old('recaptcha_secret_key', $settings['recaptcha_secret_key'] ?? '') }}"
                    placeholder="6Le...">
                <div class="form-hint">Keep this private — used on the server to verify tokens.</div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-admin btn-admin-primary" style="padding:12px 36px;">
        <i class="fas fa-save"></i> Save API Settings
    </button>
</form>

@endsection
