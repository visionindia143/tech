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

    {{-- Razorpay API Keys (shared) --}}
    <div class="panel">
        <div class="panel-header"><h2><i class="fas fa-credit-card" style="margin-right:8px;color:var(--admin-primary);"></i> Razorpay API Keys</h2></div>
        <div class="panel-body">
            <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:16px;">
                These keys are shared across all payment-enabled modules (Adoption &amp; Vaccination Booking).
                @if(empty($settings['razorpay_key_id']) || empty($settings['razorpay_key_secret']))
                    <br><strong class="text-danger">⚠️ Warning:</strong> Razorpay keys are not configured. Payments will fail until you add your API keys.
                @endif
            </p>
            <div class="form-row">
                <div class="form-group">
                    <label>Razorpay Key ID</label>
                    <input type="text" name="razorpay_key_id" class="form-control" value="{{ $settings['razorpay_key_id'] ?? '' }}" placeholder="rzp_live_...">
                </div>
                <div class="form-group">
                    <label>Razorpay Key Secret</label>
                    <input type="password" name="razorpay_key_secret" class="form-control" value="{{ $settings['razorpay_key_secret'] ?? '' }}" placeholder="••••••••">
                    <div class="form-hint">Keep this secret — used to verify payment signatures server-side.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Adoption Module Settings --}}
    <div class="panel">
        <div class="panel-header"><h2><i class="fas fa-paw" style="margin-right:8px;color:var(--admin-primary);"></i> Adoption Module Settings</h2></div>
        <div class="panel-body">
            <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:20px;">
                Control payment and OTP verification for the <strong>Pet Adoption</strong> booking form. The fee amount is set per-pet in the pet's record.
            </p>
            <div class="form-row">
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="adoption_payment_enabled" value="1"
                               {{ ($settings['adoption_payment_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                               style="width:18px;height:18px;">
                        <span style="font-weight:600;">Enable Payment for Adoption</span>
                    </label>
                    <div class="form-hint">When enabled, clients must pay the pet's adoption fee via Razorpay before the booking is confirmed.</div>
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="adoption_otp_enabled" value="1"
                               {{ ($settings['adoption_otp_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                               style="width:18px;height:18px;">
                        <span style="font-weight:600;">Enable WhatsApp OTP for Adoption</span>
                    </label>
                    <div class="form-hint">Requires MSG91 to be configured below. When enabled, clients must verify their phone via WhatsApp OTP before submitting an adoption booking.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Vaccination Booking Module Settings --}}
    <div class="panel">
        <div class="panel-header"><h2><i class="fas fa-syringe" style="margin-right:8px;color:var(--admin-primary);"></i> Vaccination Booking Settings</h2></div>
        <div class="panel-body">
            <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:20px;">
                Control payment and OTP verification for the <strong>Vaccination Booking</strong> form.
            </p>
            <div class="form-row">
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="booking_payment_enabled" value="1"
                               {{ ($settings['booking_payment_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                               style="width:18px;height:18px;">
                        <span style="font-weight:600;">Enable Payment for Vaccination Booking</span>
                    </label>
                    <div class="form-hint">When enabled, clients must pay the booking fee below via Razorpay before the appointment is confirmed.</div>
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="booking_otp_enabled" value="1"
                               {{ ($settings['booking_otp_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                               style="width:18px;height:18px;">
                        <span style="font-weight:600;">Enable WhatsApp OTP for Vaccination Booking</span>
                    </label>
                    <div class="form-hint">Requires MSG91 to be configured below. When enabled, clients must verify their phone via WhatsApp OTP before submitting a vaccination booking.</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Booking Fee Amount (₹)</label>
                    <input type="number" name="booking_fee_amount" class="form-control" min="0" step="1"
                           value="{{ $settings['booking_fee_amount'] ?? '0' }}" placeholder="e.g. 100">
                    <div class="form-hint">Flat fee charged per vaccination booking (in ₹). Set to 0 to disable the fee even if payment is enabled.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Appointment Booking Module Settings --}}
    <div class="panel">
        <div class="panel-header"><h2><i class="fas fa-calendar-check" style="margin-right:8px;color:var(--admin-primary);"></i> Appointment Booking Settings</h2></div>
        <div class="panel-body">
            <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:20px;">
                Control payment and OTP verification for the <strong>Appointment Booking</strong> form. Uses the same Razorpay keys and MSG91 configuration.
            </p>
            <div class="form-row">
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="appt_payment_enabled" value="1"
                               {{ ($settings['appt_payment_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                               style="width:18px;height:18px;">
                        <span style="font-weight:600;">Enable Payment for Appointment Booking</span>
                    </label>
                    <div class="form-hint">When enabled, clients must pay the booking fee below via Razorpay before the appointment is confirmed.</div>
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="appt_otp_enabled" value="1"
                               {{ ($settings['appt_otp_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                               style="width:18px;height:18px;">
                        <span style="font-weight:600;">Enable WhatsApp OTP for Appointment Booking</span>
                    </label>
                    <div class="form-hint">Requires MSG91 to be configured below. When enabled, clients must verify their phone via WhatsApp OTP before submitting an appointment booking.</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Appointment Booking Fee Amount (₹)</label>
                    <input type="number" name="appt_fee_amount" class="form-control" min="0" step="1"
                           value="{{ $settings['appt_fee_amount'] ?? '0' }}" placeholder="e.g. 100">
                    <div class="form-hint">Flat fee charged per appointment booking (in ₹). Set to 0 to disable the fee even if payment is enabled.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- MSG91 WhatsApp --}}
    <div class="panel">
        <div class="panel-header"><h2><i class="fab fa-whatsapp" style="margin-right:8px;color:#25D366;"></i> MSG91 WhatsApp Settings</h2></div>
        <div class="panel-body">
            <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:20px;">
                Required for WhatsApp OTP and booking status notifications. Per-module OTP toggles above will only work when MSG91 is enabled and configured here.<br>
                Template used: <code>lms_universal_notification</code> — <em>Hello &#123;&#123;1&#125;&#125;, &#123;&#123;2&#125;&#125;, Contact: &#123;&#123;3&#125;&#125;</em>
            </p>
            <div class="form-row">
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;">
                        <input type="checkbox" name="msg91_enabled" value="1" {{ ($settings['msg91_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                        Enable MSG91 WhatsApp Integration
                    </label>
                    <div class="form-hint">Master toggle — must be ON for any WhatsApp OTP or notification to work.</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>MSG91 Auth Key</label>
                    <input type="password" name="msg91_authkey" class="form-control" value="{{ $settings['msg91_authkey'] ?? '' }}" placeholder="Your MSG91 authkey">
                </div>
                <div class="form-group">
                    <label>Integrated WhatsApp Number</label>
                    <input type="text" name="msg91_integrated_number" class="form-control" value="{{ $settings['msg91_integrated_number'] ?? '918619218048' }}" placeholder="918619218048">
                    <div class="form-hint">Your MSG91 integrated number (with country code, no +).</div>
                </div>
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
        <i class="fas fa-save"></i> Save All Settings
    </button>
</form>

<script>
async function testRazorpayConnection() {
    const btn = document.getElementById('testRazorpayBtn');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';

    try {
        const response = await fetch('{{ route("admin.settings.test-razorpay") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('✅ ' + data.message);
            btn.innerHTML = '<i class="fas fa-check"></i> Connected';
            btn.className = 'btn btn-sm btn-success ml-2';
        } else {
            alert('❌ ' + data.message);
            btn.innerHTML = '<i class="fas fa-times"></i> Failed';
            btn.className = 'btn btn-sm btn-danger ml-2';
        }
    } catch (error) {
        alert('❌ Connection error: ' + error.message);
        btn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error';
        btn.className = 'btn btn-sm btn-warning ml-2';
    }

    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        btn.className = 'btn btn-sm btn-outline-success ml-2';
    }, 3000);
}
</script>
@endsection
