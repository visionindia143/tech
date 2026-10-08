<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — {{ $adminSettings['site_name'] }}</title>
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
        .input-wrap i.icon { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9CA3AF; font-size:0.9rem; }
        .toggle-eye {
            position:absolute; right:13px; top:50%; transform:translateY(-50%);
            color:#9CA3AF; background:none; border:none; cursor:pointer; font-size:0.9rem; padding:0;
        }
        .form-control {
            width:100%; padding:11px 38px 11px 38px;
            border:1px solid #E5E7EB; border-radius:10px;
            font-family:'Outfit',sans-serif; font-size:0.9rem;
            transition:border 0.2s; color:#111827;
        }
        .form-control:focus { outline:none; border-color:#FF6B35; box-shadow:0 0 0 3px rgba(255,107,53,0.1); }
        .form-control.is-invalid { border-color:#EF4444; }
        .btn-submit {
            width:100%; padding:13px;
            background:#FF6B35; color:white; border:none; border-radius:10px;
            font-family:'Outfit',sans-serif; font-size:0.95rem; font-weight:700; cursor:pointer;
            transition:background 0.2s; display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .btn-submit:hover { background:#E85520; }
        .alert { border-radius:10px; padding:12px 16px; font-size:0.85rem; margin-bottom:18px; display:flex; gap:8px; align-items:start; }
        .alert-success { background:#ECFDF5; color:#065F46; border:1px solid #A7F3D0; }
        .alert-danger { background:#FEF2F2; color:#991B1B; border:1px solid #FECACA; }
        .error-msg { color:#EF4444; font-size:0.78rem; margin-top:4px; }
        .back-link { display:block; text-align:center; margin-top:20px; font-size:0.85rem; color:#FF6B35; text-decoration:none; font-weight:600; }
        .back-link:hover { text-decoration:underline; }
        @media (max-width:480px) { .auth-card { padding:32px 24px; } }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-logo">
        <div class="auth-logo-icon"><i class="fas fa-lock"></i></div>
        <h1>Set New Password</h1>
        <p>Enter a strong new password for your account</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i>
            <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
        </div>
    @endif

    <form action="{{ route('admin.password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label for="password">New Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock icon"></i>
                <input type="password" id="password" name="password"
                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       required minlength="8" placeholder="Min. 8 characters">
                <button type="button" class="toggle-eye" onclick="togglePass('password',this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock icon"></i>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" required minlength="8" placeholder="Repeat new password">
                <button type="button" class="toggle-eye" onclick="togglePass('password_confirmation',this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-check"></i> Reset Password
        </button>
    </form>

    <a href="{{ route('admin.password.request') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Forgot Password
    </a>
</div>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Auto-focus on password field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('password').focus();
});
</script>
</body>
</html>