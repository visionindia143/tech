@extends('layouts.admin')
@section('title', 'API & Integrations')
@section('styles')
    <style>
        .save-btn-fixed {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        .save-btn-fixed .btn-admin {
            padding: 12px 28px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
        }
    </style>
@endsection
@section('content')
    <form action="{{ route('admin.integrations.update') }}" method="POST" enctype="multipart/form-data" class="admin-form">
        @csrf @method('PUT')

        <div class="save-btn-fixed">
            <button type="submit" class="btn-admin btn-admin-primary">
                <i class="fas fa-save"></i> Save All Settings
            </button>
        </div>
        {{-- Razorpay API Keys (shared) --}}
        <div class="panel">

            <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2><i class="fas fa-credit-card" style="margin-right:8px;color:var(--admin-primary);"></i> Razorpay API
                    Keys
                </h2>
                <button type="button" id="testRazorpayBtn" onclick="testRazorpayConnection()"
                    class="btn btn-sm btn-outline-success">
                    <i class="fas fa-plug"></i> Test Connection
                </button>
            </div>
            <div class="panel-body">
                <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:16px;">
                    These keys are shared across all payment-enabled modules (Adoption &amp; Vaccination Booking).
                    @if (empty($settings['razorpay_key_id']) || empty($settings['razorpay_key_secret']))
                        <br><strong class="text-danger">⚠️ Warning:</strong> Razorpay keys are not configured. Payments will
                        fail until you add your API keys.
                    @endif
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label>Razorpay Key ID</label>
                        <input type="text" name="razorpay_key_id" class="form-control"
                            value="{{ $settings['razorpay_key_id'] ?? '' }}" placeholder="rzp_live_...">
                    </div>
                    <div class="form-group">
                        <label>Razorpay Key Secret</label>
                        <input type="password" name="razorpay_key_secret" class="form-control"
                            value="{{ $settings['razorpay_key_secret'] ?? '' }}" placeholder="••••••••">
                        <div class="form-hint">Keep this secret — used to verify payment signatures server-side.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Adoption Module Settings --}}
        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-paw" style="margin-right:8px;color:var(--admin-primary);"></i> Adoption Module Settings
                </h2>
            </div>
            <div class="panel-body">
                <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:20px;">
                    Control payment and OTP verification for the <strong>Pet Adoption</strong> booking form. The fee amount
                    is set per-pet in the pet's record.
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="adoption_payment_enabled" value="1"
                                {{ ($settings['adoption_payment_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable Payment for Adoption</span>
                        </label>
                        <div class="form-hint">When enabled, clients must pay the pet's adoption fee via Razorpay before the
                            booking is confirmed.</div>
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="adoption_otp_enabled" value="1"
                                {{ ($settings['adoption_otp_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable WhatsApp OTP for Adoption</span>
                        </label>
                        <div class="form-hint">Requires MSG91 /WA Service to be configured below. When enabled, clients must verify
                            their phone via WhatsApp OTP before submitting an adoption booking.</div>
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="adoption_notify_enabled" value="1"
                                {{ ($settings['adoption_notify_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable WhatsApp Notify </span>
                        </label>
                        <div class="form-hint">When enabled, clients must got 
                            notification on their phone via WhatsApp when you change status of the booking .</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vaccination Booking Module Settings --}}
        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-syringe" style="margin-right:8px;color:var(--admin-primary);"></i> Vaccination Booking
                    Settings</h2>
            </div>
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
                        <div class="form-hint">When enabled, clients must pay the booking fee below via Razorpay before the
                            appointment is confirmed.</div>
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="booking_otp_enabled" value="1"
                                {{ ($settings['booking_otp_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable WhatsApp OTP for Vaccination Booking</span>
                        </label>
                        <div class="form-hint">Requires MSG91 /WA Service to be configured below. When enabled, clients must verify
                            their phone via WhatsApp OTP before submitting a vaccination booking.</div>
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="booking_notify_enabled" value="1"
                                {{ ($settings['booking_notify_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable WhatsApp Notify </span>
                        </label>
                        <div class="form-hint">When enabled, clients must got 
                            notification on their phone via WhatsApp when you change status of the booking .</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Booking Fee Amount (₹)</label>
                        <input type="number" name="booking_fee_amount" class="form-control" min="0" step="1"
                            value="{{ $settings['booking_fee_amount'] ?? '0' }}" placeholder="e.g. 100">
                        <div class="form-hint">Flat fee charged per vaccination booking (in ₹). Set to 0 to disable the fee
                            even if payment is enabled.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- diagnostic/test Booking Module Settings --}}
        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-syringe" style="margin-right:8px;color:var(--admin-primary);"></i> diagnostic Booking
                    Settings</h2>
            </div>
            <div class="panel-body">
                <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:20px;">
                    Control payment and OTP verification for the <strong>diagnostic/test Booking</strong> form.
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="diagnostic_payment_enabled" value="1"
                                {{ ($settings['diagnostic_payment_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable Payment for diagnostic Booking</span>
                        </label>
                        <div class="form-hint">When enabled, clients must pay the booking fee below via Razorpay before the
                            appointment is confirmed.</div>
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="diagnostic_otp_enabled" value="1"
                                {{ ($settings['diagnostic_otp_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable WhatsApp OTP for diagnostic Booking</span>
                        </label>
                        <div class="form-hint">Requires MSG91 /WA Service to be configured below. When enabled, clients must verify
                            their phone via WhatsApp OTP before submitting a vaccination booking.</div>
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="diagnostic_notify_enabled" value="1"
                                {{ ($settings['diagnostic_notify_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable WhatsApp Notify </span>
                        </label>
                        <div class="form-hint">When enabled, clients must got 
                            notification on their phone via WhatsApp when you change status of the booking .</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Booking Fee Amount (₹)</label>
                        <input type="number" name="booking_fee_amount" class="form-control" min="0"
                            step="1" value="{{ $settings['booking_fee_amount'] ?? '0' }}" placeholder="e.g. 100">
                        <div class="form-hint">Flat fee charged per vaccination booking (in ₹). Set to 0 to disable the fee
                            even if payment is enabled.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Appointment Booking Module Settings --}}
        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-calendar-check" style="margin-right:8px;color:var(--admin-primary);"></i> Appointment
                    Booking Settings</h2>
            </div>
            <div class="panel-body">
                <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:20px;">
                    Control payment and OTP verification for the <strong>Appointment Booking</strong> form. Uses the same
                    Razorpay keys and MSG91 configuration.
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="appt_payment_enabled" value="1"
                                {{ ($settings['appt_payment_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable Payment for Appointment Booking</span>
                        </label>
                        <div class="form-hint">When enabled, clients must pay the booking fee below via Razorpay before the
                            appointment is confirmed.</div>
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="appt_otp_enabled" value="1"
                                {{ ($settings['appt_otp_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable WhatsApp OTP for Appointment Booking</span>
                        </label>
                        <div class="form-hint">Requires MSG91 /WA Service to be configured below. When enabled, clients must verify
                            their phone via WhatsApp OTP before submitting an appointment booking.</div>
                    </div>

                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="appt_notify_enabled" value="1"
                                {{ ($settings['appt_notify_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                style="width:18px;height:18px;">
                            <span style="font-weight:600;">Enable WhatsApp Notify </span>
                        </label>
                        <div class="form-hint">When enabled, clients must got 
                            notification on their phone via WhatsApp when you change status of the booking .</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Appointment Booking Fee Amount (₹)</label>
                        <input type="number" name="appt_fee_amount" class="form-control" min="0" step="1"
                            value="{{ $settings['appt_fee_amount'] ?? '0' }}" placeholder="e.g. 100">
                        <div class="form-hint">Flat fee charged per appointment booking (in ₹). Set to 0 to disable the fee
                            even if payment is enabled.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MSG91 WhatsApp --}}
        <div class="panel">
            <div class="panel-header">
                <h2><i class="fab fa-whatsapp" style="margin-right:8px;color:#25D366;"></i> MSG91 WhatsApp Settings</h2>
            </div>
            <div class="panel-body">
                <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:20px;">
                    Required for WhatsApp OTP and booking status notifications. Per-module OTP toggles above will only work
                    when MSG91 is enabled and configured here.<br>
                    Template used: <code>lms_universal_notification</code> — <em>Hello &#123;&#123;1&#125;&#125;,
                        &#123;&#123;2&#125;&#125;, Contact: &#123;&#123;3&#125;&#125;</em>
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="msg91_enabled" value="1"
                                {{ ($settings['msg91_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            Enable MSG91 WhatsApp Integration
                        </label>
                        <div class="form-hint">Master toggle — must be ON for any WhatsApp OTP or notification to work.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>MSG91 Auth Key</label>
                        <input type="password" name="msg91_authkey" class="form-control"
                            value="{{ $settings['msg91_authkey'] ?? '' }}" placeholder="Your MSG91 authkey">
                    </div>
                    <div class="form-group">
                        <label>Integrated WhatsApp Number</label>
                        <input type="text" name="msg91_integrated_number" class="form-control"
                            value="{{ $settings['msg91_integrated_number'] ?? '918619218048' }}"
                            placeholder="918619218048">
                        <div class="form-hint">Your MSG91 integrated number (with country code, no +).</div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Wa WhatsApp --}}
        <div class="panel">
            <div class="panel-header">
                <h2><i class="fab fa-whatsapp" style="margin-right:8px;color:#25D366;"></i> Wa WhatsApp Settings</h2>
            </div>
            <div class="panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="wa_enabled" value="1"
                                {{ ($settings['wa_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            Enable Wa WhatsApp Integration
                        </label>
                        <div class="form-hint">Master toggle — must be ON for any WhatsApp OTP or notification to work.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Wa Api Key</label>
                        <input type="password" name="wa_api_key" class="form-control"
                            value="{{ $settings['wa_api_key'] ?? '' }}" placeholder="Your Wa Api key">
                    </div>
                    <div class="form-group">
                        <label>Wa Session Key</label>
                        <input type="text" name="wa_session_key" class="form-control"
                            value="{{ $settings['wa_session_key'] ?? '' }}" placeholder="You Session key">

                    </div>
                    <div class="form-group">
                        <label>Wa Api Url</label>
                        <input type="text" name="wa_api_url" class="form-control"
                            value="{{ $settings['wa_api_url'] ?? '' }}" placeholder="You wa api url ">

                    </div>
                          <div class="form-group">
                        <label>Admin Whatsapp Forward Number</label>
                        <input type="number" name="wtsp_forward_no" class="form-control"
                            value="{{ $settings['wtsp_forward_no'] ?? '' }}" placeholder="91xxxxx ">

                    </div>
                    
                </div>
            </div>
            <div class="panel" style="margin:20px;">
                <div class="panel-header">
                    <h2><i class="fab fa-whatsapp" style="margin-right:8px;color:#25D366;"></i>Test Wa Api </h2>
                </div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" id="wa_phone" class="form-control" placeholder="+91XXXXXXXXXX">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <input type="text" id="wa_message" class="form-control"
                                placeholder="Type your test message...">
                        </div>
                        <div class="form-group">
                            <button type="button"
                                class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2"
                                onclick="sendWA()">
                                <i class="fas fa-paper-plane"></i>
                                Send
                            </button>
                        </div>
                        <div id="wa_result" style="margin-top:10px;"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-key" style="margin-right:8px;color:var(--admin-primary);"></i> Tiny Editor</h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>TinyMCE API Key</label>
                    <input type="text" name="tinymce_api_key" class="form-control"
                        value="{{ old('tinymce_api_key', $settings['tinymce_api_key'] ?? '') }}"
                        placeholder="Paste your TinyMCE API key">
                    <div class="form-hint">This key will be used automatically in blog/page editor screens.</div>
                </div>

                <div class="form-group">
                    <label>Tiny Drive API Key (Optional)</label>
                    <input type="text" name="tiny_drive_api_key" class="form-control"
                        value="{{ old('tiny_drive_api_key', $settings['tiny_drive_api_key'] ?? '') }}"
                        placeholder="Optional Tiny Drive key">
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-puzzle-piece" style="margin-right:8px;color:var(--admin-primary);"></i> Other Extra
                    API/Config</h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Extra API Settings</label>
                    <textarea name="extra_api_settings" class="form-control" rows="8"
                        placeholder="You can store any additional API keys, notes, JSON snippets, or integration config here.">{{ old('extra_api_settings', $settings['extra_api_settings'] ?? '') }}</textarea>
                    <div class="form-hint">Tip: For multiple entries, keep one key/value per line.</div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-shield-alt" style="margin-right:8px;color:var(--admin-primary);"></i> Google
                    reCAPTCHA v2</h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="recaptcha_enabled" value="1"
                            {{ ($settings['recaptcha_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                            style="width:18px;height:18px;cursor:pointer;">
                        <span>Enable reCAPTCHA on Contact / Adoption / Vaccination forms</span>
                    </label>
                    <div class="form-hint">When enabled, a "I'm not a robot" checkbox will appear on all public forms to
                        block bots.</div>
                </div>
                <div class="form-group">
                    <label>Site Key</label>
                    <input type="text" name="recaptcha_site_key" class="form-control"
                        value="{{ old('recaptcha_site_key', $settings['recaptcha_site_key'] ?? '') }}"
                        placeholder="6Le...">
                    <div class="form-hint">Shown to users — used in the widget. Get keys at <a
                            href="https://www.google.com/recaptcha/admin/create" target="_blank"
                            rel="noopener">google.com/recaptcha</a>.</div>
                </div>
                <div class="form-group">
                    <label>Secret Key</label>
                    <input type="text" name="recaptcha_secret_key" class="form-control"
                        value="{{ old('recaptcha_secret_key', $settings['recaptcha_secret_key'] ?? '') }}"
                        placeholder="6Le...">
                    <div class="form-hint">Keep this private — used on the server to verify tokens.</div>
                </div>
            </div>
        </div>


        {{-- 
        <button type="submit" class="btn-admin btn-admin-primary" style="padding:12px 36px;">
            <i class="fas fa-save"></i> Save All Settings
        </button> --}}
    </form>




    <script>
        async function testRazorpayConnection() {
            const btn = document.getElementById('testRazorpayBtn');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';

            try {
                const response = await fetch('{{ route('admin.settings.test-razorpay') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const msg91 = document.querySelector('[name="msg91_enabled"]');
            const wa = document.querySelector('[name="wa_enabled"]');

            msg91.addEventListener('change', function() {
                if (this.checked) {
                    wa.checked = false;
                }
            });

            wa.addEventListener('change', function() {
                if (this.checked) {
                    msg91.checked = false;
                }
            });

        });
    </script>

    <script>
        async function sendWA() {

            const phone = document.getElementById('wa_phone').value.trim();
            const message = document.getElementById('wa_message').value.trim();
            const resultBox = document.getElementById('wa_result');

            const apiUrl = document.querySelector('input[name="wa_api_url"]').value.trim();
            const apiKey = document.querySelector('input[name="wa_api_key"]').value.trim();
            const session = document.querySelector('input[name="wa_session_key"]').value.trim();

            // 🔍 Validation
            if (!apiUrl || !apiKey || !session) {
                resultBox.innerHTML = "⚠️ API URL / KEY / SESSION missing";
                return;
            }

            if (!phone) {
                resultBox.innerHTML = "⚠️ Enter phone number";
                return;
            }

            resultBox.innerHTML = "⏳ Sending...";

            try {
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "x-api-key": apiKey,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        session: session,
                        to: phone,
                        message: message || "Test message 🚀",
                        contactName: "Admin Test",
                    }),
                });

                const data = await response.json();

                console.log("STATUS:", response.status);
                console.log("DATA:", data);

                if (response.ok) {
                    resultBox.innerHTML = "✅ Message Sent Successfully";
                } else {
                    resultBox.innerHTML = "❌ Failed: " + (data.message || "Unknown error");
                }

            } catch (error) {
                console.error(error);
                resultBox.innerHTML = "❌ Error: " + error.message;
            }
        }
    </script>
@endsection
