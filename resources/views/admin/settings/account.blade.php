@extends('layouts.admin')
@section('title', 'My Account')

@section('styles')
<style>
    .account-wrap {
        display: flex;
        flex-direction: column;
        gap: 24px;
        max-width: 1100px;
    }

    .account-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 24px;
        background: linear-gradient(135deg, var(--admin-primary), #4338CA);
        border-radius: 14px;
        color: #fff;
        margin-bottom: 8px;
    }
    .account-header-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(255,255,255,0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .account-header h1 {
        font-size: 1.35rem;
        margin: 0 0 3px 0;
        color: #fff;
    }
    .account-header p {
        opacity: 0.85;
        margin: 0;
        font-size: 0.88rem;
    }

    .panel-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(99,102,241,0.1);
        color: var(--admin-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 0.95rem;
    }

    .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }

    .info-banner {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        background: #FFF7ED;
        border-left: 4px solid #F97316;
        border-radius: 8px;
        margin-bottom: 22px;
        font-size: 0.88rem;
        color: #7C2D12;
        line-height: 1.55;
    }
    .info-banner i { color: #F97316; font-size: 1.05rem; margin-top: 2px; }
    .info-banner code {
        background: rgba(249,115,22,0.12);
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 0.82rem;
        color: #9A3412;
    }

    .password-wrap { position: relative; }
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: 0;
        color: var(--admin-gray, #6B7280);
        cursor: pointer;
        padding: 4px;
        font-size: 0.9rem;
    }
    .password-toggle:hover { color: var(--admin-primary); }

    .action-row {
        display: flex;
        gap: 14px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 6px;
    }

    .test-mail-box {
        margin-top: 22px;
        padding: 18px;
        background: #F9FAFB;
        border: 1px dashed #D1D5DB;
        border-radius: 10px;
    }
    .test-mail-box label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        display: block;
        margin-bottom: 8px;
    }
    .test-mail-row {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .test-mail-row input {
        flex: 1;
        min-width: 220px;
    }

    @media (max-width: 768px) {
        .form-row-2, .form-row-3 { grid-template-columns: 1fr; gap: 16px; }
        .account-header { flex-direction: column; align-items: flex-start; text-align: left; }
    }
</style>
@endsection

@section('content')
<div class="account-wrap">

    {{-- ─── Page Header ─── --}}
    <div class="account-header">
        <div class="account-header-icon"><i class="fas fa-user-shield"></i></div>
        <div>
            <h1>My Account</h1>
            <p>Manage your login credentials, password, and email sending settings.</p>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         SECTION 1 — CHANGE LOGIN EMAIL
         ════════════════════════════════════════ --}}
    <form action="{{ route('admin.account.update-email') }}" method="POST" class="admin-form" style="margin:0;">
        @csrf @method('PUT')

        <div class="panel">
            <div class="panel-header">
                <h2>
                    <span class="panel-header-icon"><i class="fas fa-envelope"></i></span>
                    Login Email
                </h2>
            </div>
            <div class="panel-body">

                @if(session('email_success'))
                    <div class="admin-alert admin-alert-success" style="margin-bottom:20px;">
                        <i class="fas fa-check-circle"></i> {{ session('email_success') }}
                    </div>
                @endif

                @if($errors->has('email') || $errors->has('password'))
                    <div class="admin-alert admin-alert-danger" style="margin-bottom:20px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach($errors->get('email') as $e)<div>{{ $e }}</div>@endforeach
                            @foreach($errors->get('password') as $e)<div>{{ $e }}</div>@endforeach
                        </div>
                    </div>
                @endif

                <div class="form-row-2">
                    <div class="form-group">
                        <label>New Login Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}"
                               required placeholder="you@example.com">
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Used to log in and receive password reset links.
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Current Password <span style="color:#EF4444;">*</span></label>
                        <div class="password-wrap">
                            <input type="password" name="password" class="form-control"
                                   required placeholder="Enter your current password" id="emailConfirmPwd">
                            <button type="button" class="password-toggle" onclick="togglePwd('emailConfirmPwd', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-hint">
                            <i class="fas fa-lock"></i>
                            Required to confirm the email change.
                        </div>
                    </div>
                </div>

                <div class="action-row">
                    <button type="submit" class="btn-admin btn-admin-primary">
                        <i class="fas fa-save"></i> Update Email
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- ════════════════════════════════════════
         SECTION 2 — CHANGE PASSWORD
         ════════════════════════════════════════ --}}
    <form action="{{ route('admin.account.update-password') }}" method="POST" class="admin-form" style="margin:0;">
        @csrf @method('PUT')

        <div class="panel">
            <div class="panel-header">
                <h2>
                    <span class="panel-header-icon"><i class="fas fa-lock"></i></span>
                    Change Password
                </h2>
            </div>
            <div class="panel-body">

                @if(session('password_success'))
                    <div class="admin-alert admin-alert-success" style="margin-bottom:20px;">
                        <i class="fas fa-check-circle"></i> {{ session('password_success') }}
                    </div>
                @endif

                @if($errors->has('current_password'))
                    <div class="admin-alert admin-alert-danger" style="margin-bottom:20px;">
                        <i class="fas fa-exclamation-circle"></i> {{ $errors->first('current_password') }}
                    </div>
                @endif

                @if($errors->has('password') && !$errors->has('email'))
                    <div class="admin-alert admin-alert-danger" style="margin-bottom:20px;">
                        <i class="fas fa-exclamation-circle"></i> {{ $errors->first('password') }}
                    </div>
                @endif

                <div class="form-row-3">
                    <div class="form-group">
                        <label>Current Password</label>
                        <div class="password-wrap">
                            <input type="password" name="current_password" class="form-control"
                                   required placeholder="Current password" id="curPwd">
                            <button type="button" class="password-toggle" onclick="togglePwd('curPwd', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <div class="password-wrap">
                            <input type="password" name="password" class="form-control"
                                   required minlength="8" placeholder="Min. 8 characters" id="newPwd">
                            <button type="button" class="password-toggle" onclick="togglePwd('newPwd', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-hint">
                            <i class="fas fa-shield-alt"></i>
                            Use at least 8 characters with a mix of letters and numbers.
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <div class="password-wrap">
                            <input type="password" name="password_confirmation" class="form-control"
                                   required minlength="8" placeholder="Repeat new password" id="confirmPwd">
                            <button type="button" class="password-toggle" onclick="togglePwd('confirmPwd', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="action-row">
                    <button type="submit" class="btn-admin btn-admin-primary">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- ════════════════════════════════════════
         SECTION 3 — SMTP / MAIL SETTINGS
         ════════════════════════════════════════ --}}
    <form action="{{ route('admin.account.update-smtp') }}" method="POST" class="admin-form" style="margin:0;">
        @csrf @method('PUT')

        <div class="panel">
            <div class="panel-header">
                <h2>
                    <span class="panel-header-icon"><i class="fas fa-paper-plane"></i></span>
                    SMTP / Mail Settings
                </h2>
            </div>
            <div class="panel-body">

                @if(session('smtp_success'))
                    <div class="admin-alert admin-alert-success" style="margin-bottom:20px;">
                        <i class="fas fa-check-circle"></i> {{ session('smtp_success') }}
                    </div>
                @endif

                <div class="info-banner">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        These settings override <code>.env</code> values at runtime. They are required for the
                        <strong>"Forgot Password"</strong> email to work. Leave <strong>SMTP Host</strong> empty to fall back to <code>.env</code>.
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>SMTP Host</label>
                        <input type="text" name="mail_host" class="form-control"
                               value="{{ old('mail_host', $settings['mail_host'] ?? '') }}"
                               placeholder="smtp.gmail.com">
                    </div>
                    <div class="form-group">
                        <label>SMTP Port</label>
                        <input type="number" name="mail_port" class="form-control"
                               value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}"
                               placeholder="587">
                    </div>
                    <div class="form-group">
                        <label>Username / Email</label>
                        <input type="text" name="mail_username" class="form-control"
                               value="{{ old('mail_username', $settings['mail_username'] ?? '') }}"
                               placeholder="your@gmail.com">
                    </div>
                    <div class="form-group">
                        <label>Password / App Password</label>
                        <div class="password-wrap">
                            <input type="password" name="mail_password" class="form-control"
                                   placeholder="Leave blank to keep existing" id="mailPwd">
                            <button type="button" class="password-toggle" onclick="togglePwd('mailPwd', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Leave blank to keep the saved password unchanged.
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Encryption</label>
                        <select name="mail_encryption" class="form-control">
                            @foreach(['tls' => 'TLS (Recommended)', 'ssl' => 'SSL', 'starttls' => 'STARTTLS', '' => 'None'] as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('mail_encryption', $settings['mail_encryption'] ?? 'tls') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>From Address</label>
                        <input type="email" name="mail_from_address" class="form-control"
                               value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}"
                               placeholder="noreply@yourdomain.com">
                    </div>
                    <div class="form-group">
                        <label>From Name</label>
                        <input type="text" name="mail_from_name" class="form-control"
                               value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}"
                               placeholder="{{ $settings['site_name'] ?? 'PawCare' }}">
                    </div>
                </div>

                <div class="action-row">
                    <button type="submit" class="btn-admin btn-admin-primary">
                        <i class="fas fa-save"></i> Save SMTP Settings
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- ════════════════════════════════════════
         SECTION 4 — NOTIFICATION EMAILS
         ════════════════════════════════════════ --}}
    <form action="{{ route('admin.account.update-notifications') }}" method="POST" class="admin-form" style="margin:0;">
        @csrf @method('PUT')

        <div class="panel">
            <div class="panel-header">
                <h2>
                    <span class="panel-header-icon"><i class="fas fa-bell"></i></span>
                    Form Notification Emails
                </h2>
            </div>
            <div class="panel-body">

                @if(session('notif_success'))
                    <div class="admin-alert admin-alert-success" style="margin-bottom:20px;">
                        <i class="fas fa-check-circle"></i> {{ session('notif_success') }}
                    </div>
                @endif

                <div class="info-banner">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        Whenever a visitor submits a <strong>Contact</strong>, <strong>Vaccination Booking</strong>, or <strong>Adoption Booking</strong> form, a notification email will be sent to the address(es) below.
                        You can enter <strong>multiple emails</strong> separated by commas. Requires SMTP to be configured above.
                    </div>
                </div>

                <div class="form-group" style="max-width:620px;">
                    <label style="font-weight:600;margin-bottom:8px;display:block;">
                        Notification Email Address(es)
                    </label>
                    <input type="text" name="notification_email" class="form-control"
                           value="{{ old('notification_email', $settings['notification_email'] ?? '') }}"
                           placeholder="admin@example.com, owner@example.com">
                    <div class="form-hint" style="margin-top:8px;">
                        <i class="fas fa-info-circle"></i>
                        Separate multiple emails with a comma. E.g. <code>admin@domain.com, sales@domain.com</code>
                    </div>
                </div>

                <div style="margin-top:6px;padding:14px 18px;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;font-size:0.85rem;color:#374151;max-width:620px;">
                    <strong style="display:block;margin-bottom:10px;color:#111827;">
                        <i class="fas fa-paper-plane" style="color:var(--admin-primary);margin-right:6px;"></i>
                        Which forms trigger a notification?
                    </strong>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <i class="fas fa-envelope" style="color:#3B82F6;width:18px;"></i>
                            <span><strong>Contact Form</strong> — when a visitor sends a message</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <i class="fas fa-syringe" style="color:#8B5CF6;width:18px;"></i>
                            <span><strong>Vaccination Booking</strong> — when a vaccination appointment is booked</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <i class="fas fa-paw" style="color:#F97316;width:18px;"></i>
                            <span><strong>Adoption Booking</strong> — when someone applies to adopt a pet</span>
                        </div>
                    </div>
                </div>

                <div class="action-row" style="margin-top:20px;">
                    <button type="submit" class="btn-admin btn-admin-primary">
                        <i class="fas fa-save"></i> Save Notification Settings
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- ════════════════════════════════════════
         SECTION 5 — TEST EMAIL (SEPARATE FORM — not nested!)
         ════════════════════════════════════════ --}}
    <form action="{{ route('admin.settings.test-mail') }}" method="POST" class="admin-form" style="margin:0;">
        @csrf

        <div class="panel">
            <div class="panel-header">
                <h2>
                    <span class="panel-header-icon"><i class="fas fa-vial"></i></span>
                    Send Test Email
                </h2>
            </div>
            <div class="panel-body">

                @if(session('test_mail_success'))
                    <div class="admin-alert admin-alert-success" style="margin-bottom:20px;">
                        <i class="fas fa-check-circle"></i> {{ session('test_mail_success') }}
                    </div>
                @endif

                @if(session('test_mail_error'))
                    <div class="admin-alert admin-alert-danger" style="margin-bottom:20px;">
                        <i class="fas fa-exclamation-circle"></i> {{ session('test_mail_error') }}
                    </div>
                @endif

                <p style="font-size:0.9rem;color:#6B7280;margin:0 0 16px 0;">
                    Save your SMTP settings above first, then send a test email to verify everything works.
                </p>

                <div class="test-mail-row">
                    <input type="email" name="test_email" class="form-control"
                           placeholder="test@example.com" required>
                    <button type="submit" class="btn-admin btn-admin-primary">
                        <i class="fas fa-paper-plane"></i> Send Test Email
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
    function togglePwd(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection