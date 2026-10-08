<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP — {{ $adminSettings['site_name'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Outfit',sans-serif;
            background:linear-gradient(135deg,#1A1B2F 0%,#252742 50%,#1A1B2F 100%);
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
        }
        .auth-card {
            background:white;
            border-radius:20px;
            padding:44px 40px;
            width:100%;
            max-width:420px;
            box-shadow:0 25px 60px rgba(0,0,0,0.3);
        }
        .auth-logo { text-align:center; margin-bottom:28px; }
        .auth-logo-icon {
            width:56px; height:56px;
            background:linear-gradient(135deg,#FF6B35,#E85520);
            border-radius:16px;
            display:inline-flex; align-items:center; justify-content:center;
            color:white; font-size:1.4rem; margin-bottom:14px;
        }
        .auth-logo h1 { font-size:1.4rem; font-weight:700; color:#111827; margin-bottom:6px; }
        .auth-logo p  { font-size:0.85rem; color:#6B7280; line-height:1.5; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-weight:600; font-size:0.83rem; margin-bottom:6px; color:#111827; }
        .input-wrap { position:relative; }
        .input-wrap i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9CA3AF; font-size:0.9rem; }
        .form-control {
            width:100%; padding:11px 14px 11px 38px;
            border:1px solid #E5E7EB; border-radius:10px;
            font-family:'Outfit',sans-serif; font-size:0.9rem;
            transition:border 0.2s; color:#111827;
        }
        .form-control:focus { outline:none; border-color:#FF6B35; box-shadow:0 0 0 3px rgba(255,107,53,0.1); }
        .otp-input { text-align:center; font-size:1.2rem; font-weight:600; letter-spacing:0.5rem; }
        .btn-submit {
            width:100%; padding:13px;
            background:#FF6B35; color:white; border:none; border-radius:10px;
            font-family:'Outfit',sans-serif; font-size:0.95rem; font-weight:700; cursor:pointer;
            transition:background 0.2s; display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .btn-submit:hover { background:#E85520; }
        .alert { border-radius:10px; padding:12px 16px; font-size:0.85rem; margin-bottom:18px; display:flex; gap:8px; align-items:start; }
        .alert-success { background:#ECFDF5; color:#065F46; border:1px solid #A7F3D0; }
        .alert-danger  { background:#FEF2F2; color:#991B1B; border:1px solid #FECACA; }
        .error-msg { color:#EF4444; font-size:0.78rem; margin-top:4px; }
        .back-link { display:block; text-align:center; margin-top:20px; font-size:0.85rem; color:#FF6B35; text-decoration:none; font-weight:600; }
        .back-link:hover { text-decoration:underline; }
        .resend-link { display:block; text-align:center; margin-top:15px; font-size:0.8rem; color:#6B7280; text-decoration:none; }
        .resend-link:hover { color:#FF6B35; }
        @media (max-width:480px) { .auth-card { padding:32px 24px; } }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-logo">
        <div class="auth-logo-icon"><i class="fas fa-shield-alt"></i></div>
        <h1>Verify OTP</h1>
        <p>Enter the 6-digit OTP sent to your email</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('otp') ?: $errors->first('email') }}</div>
    @endif

    <form action="{{ route('admin.password.verify-otp.post') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label for="otp">OTP Code</label>
            <div class="input-wrap">
                <i class="fas fa-key"></i>
                <input type="text" id="otp" name="otp" class="form-control otp-input"
                       value="{{ old('otp') }}" maxlength="6" required autofocus
                       placeholder="000000" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
            <div style="text-align:center; margin-top:8px; font-size:0.8rem; color:#6B7280;">
                OTP sent to: {{ $email }}
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-check"></i> Verify OTP
        </button>
    </form>

    <a href="{{ route('admin.password.request') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Forgot Password
    </a>

    <a href="{{ route('admin.password.email') }}" class="resend-link" onclick="return confirm('Send new OTP to {{ $email }}?')">
        <i class="fas fa-redo"></i> Resend OTP
    </a>
</div>

<script>
// Auto-focus and formatting for OTP input
document.getElementById('otp').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Auto-focus on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('otp').focus();
});
</script>
</body>
</html>