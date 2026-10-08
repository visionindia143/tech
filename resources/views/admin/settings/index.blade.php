@extends('layouts.admin')
@section('title', 'Site Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="admin-form">
    @csrf @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
        {{-- General Settings --}}
        <div class="panel">
            <div class="panel-header"><h2><i class="fas fa-globe" style="margin-right:8px;color:var(--admin-primary);"></i> General Settings</h2></div>
            <div class="panel-body">
                <div class="form-group"><label>Site Name</label><input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? '' }}"></div>
                <div class="form-group"><label>Site Tagline</label><input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] ?? '' }}"></div>
                <div class="form-group"><label>Email</label><input type="email" name="site_email" class="form-control" value="{{ $settings['site_email'] ?? '' }}"></div>
                <div class="form-group"><label>Phone</label><input type="text" name="site_phone" class="form-control" value="{{ $settings['site_phone'] ?? '' }}"></div>
                <div class="form-group"><label>Address</label><textarea name="site_address" class="form-control" rows="2">{{ $settings['site_address'] ?? '' }}</textarea></div>
                <div class="form-group"><label>WhatsApp Number</label><input type="text" name="whatsapp_number" class="form-control" value="{{ $settings['whatsapp_number'] ?? '' }}"></div>
            </div>
        </div>

        {{-- SEO & Branding --}}
        <div>
            <div class="panel">
                <div class="panel-header"><h2><i class="fas fa-search" style="margin-right:8px;color:var(--admin-primary);"></i> SEO Defaults</h2></div>
                <div class="panel-body">
                    <div class="form-group"><label>Default Meta Title</label><input type="text" name="default_meta_title" class="form-control" value="{{ $settings['default_meta_title'] ?? '' }}"></div>
                    <div class="form-group"><label>Default Meta Description</label><textarea name="default_meta_description" class="form-control" rows="3">{{ $settings['default_meta_description'] ?? '' }}</textarea></div>
                    <div class="form-group"><label>Default Meta Keywords</label><input type="text" name="default_meta_keywords" class="form-control" value="{{ $settings['default_meta_keywords'] ?? '' }}"></div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h2><i class="fas fa-image" style="margin-right:8px;color:var(--admin-primary);"></i> Branding</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Site Logo</label>
                        <input type="file" name="site_logo" class="form-control" accept="image/*">
                        @if(!empty($settings['site_logo']))
                            <img src="{{ asset('uploads/settings/' . $settings['site_logo']) }}" style="height:40px;margin-top:8px;">
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Favicon</label>
                        <input type="file" name="site_favicon" class="form-control" accept="image/*">
                        @if(!empty($settings['site_favicon']))
                            <img src="{{ asset('uploads/settings/' . $settings['site_favicon']) }}" style="height:32px;margin-top:8px;">
                        @endif
                </div>
            </div>
            @if(!empty($settings['razorpay_key_id']) && !empty($settings['razorpay_key_secret']))
                <div style="margin-top:15px;padding:10px;background:#f8f9fa;border-radius:4px;border-left:4px solid #28a745;">
                    <i class="fas fa-check-circle text-success"></i>
                    <strong>Razorpay Configured:</strong> API keys are set and ready for payments.
                    <button id="testRazorpayBtn" class="btn btn-sm btn-outline-success ml-2" onclick="testRazorpayConnection()">
                        <i class="fas fa-plug"></i> Test Connection
                    </button>
                </div>
            @else
                <div style="margin-top:15px;padding:10px;background:#fff3cd;border-radius:4px;border-left:4px solid #ffc107;">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    <strong>Configuration Required:</strong> Add your Razorpay API keys above to enable payments.
                    @if(config('app.env') === 'local')
                        <br><small class="text-muted">In development mode, you can test payments with mock data.</small>
                    @endif
                </div>
            @endif
        </div>
    </div>
    </div>

    {{-- Social Media --}}
    <div class="panel">
        <div class="panel-header"><h2><i class="fas fa-share-alt" style="margin-right:8px;color:var(--admin-primary);"></i> Social Media Links</h2></div>
        <div class="panel-body">
            <div class="form-row">
                <div class="form-group"><label>Facebook URL</label><input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? '' }}"></div>
                <div class="form-group"><label>Instagram URL</label><input type="url" name="instagram_url" class="form-control" value="{{ $settings['instagram_url'] ?? '' }}"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Twitter URL</label><input type="url" name="twitter_url" class="form-control" value="{{ $settings['twitter_url'] ?? '' }}"></div>
                <div class="form-group"><label>YouTube URL</label><input type="url" name="youtube_url" class="form-control" value="{{ $settings['youtube_url'] ?? '' }}"></div>
            </div>
        </div>
    </div>

    {{-- Theme Customization --}}
    <div class="panel">
        <div class="panel-header"><h2><i class="fas fa-palette" style="margin-right:8px;color:var(--admin-primary);"></i> Theme Customization</h2></div>
        <div class="panel-body">
            <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:18px;">
                Manage frontend and admin theme colors from here. These colors are applied globally.
            </p>

            <h3 style="font-size:0.95rem;margin:0 0 12px 0;color:var(--admin-dark);">Frontend Colors</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Primary Color</label>
                    <input type="color" name="theme_primary_color" class="form-control" value="{{ $settings['theme_primary_color'] ?? '#FF6B35' }}" style="height:44px;padding:6px;">
                </div>
                <div class="form-group">
                    <label>Primary Dark (Hover)</label>
                    <input type="color" name="theme_primary_dark_color" class="form-control" value="{{ $settings['theme_primary_dark_color'] ?? '#E85520' }}" style="height:44px;padding:6px;">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Secondary Color</label>
                    <input type="color" name="theme_secondary_color" class="form-control" value="{{ $settings['theme_secondary_color'] ?? '#2D3142' }}" style="height:44px;padding:6px;">
                </div>
                <div class="form-group">
                    <label>Accent Color</label>
                    <input type="color" name="theme_accent_color" class="form-control" value="{{ $settings['theme_accent_color'] ?? '#06D6A0' }}" style="height:44px;padding:6px;">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Button Text Color</label>
                    <input type="color" name="theme_button_text_color" class="form-control" value="{{ $settings['theme_button_text_color'] ?? '#FFFFFF' }}" style="height:44px;padding:6px;">
                </div>
            </div>

            <h3 style="font-size:0.95rem;margin:14px 0 12px 0;color:var(--admin-dark);">Admin Panel Colors</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Admin Primary Color</label>
                    <input type="color" name="admin_theme_primary_color" class="form-control" value="{{ $settings['admin_theme_primary_color'] ?? '#FF6B35' }}" style="height:44px;padding:6px;">
                </div>
                <div class="form-group">
                    <label>Admin Primary Dark (Hover)</label>
                    <input type="color" name="admin_theme_primary_dark_color" class="form-control" value="{{ $settings['admin_theme_primary_dark_color'] ?? '#E85520' }}" style="height:44px;padding:6px;">
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-admin btn-admin-primary" style="padding:12px 36px;">
        <i class="fas fa-save"></i> Save All Theme Settings
    </button>
</form>




@endsection
