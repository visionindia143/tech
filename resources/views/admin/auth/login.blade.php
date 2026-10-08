<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — {{ $adminSettings['site_name'] }}</title>
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
        .auth-logo {
            text-align:center;
            margin-bottom:32px;
        }
        .auth-logo img { height:50px; max-width:180px; object-fit:contain; }
        .auth-logo-icon {
            width:60px; height:60px;
            background:#FF6B35;
            border-radius:16px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            color:white;
            font-size:1.6rem;
            margin-bottom:12px;
        }
        .auth-logo h1 { font-size:1.5rem; font-weight:700; color:#111827; margin-bottom:4px; }
        .auth-logo p  { font-size:0.85rem; color:#6B7280; }

        .form-group { margin-bottom:18px; }
        .form-group label { display:block; font-weight:600; font-size:0.83rem; margin-bottom:6px; color:#111827; }
        .input-wrap { position:relative; }
        .input-wrap i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9CA3AF; font-size:0.9rem; }
        .form-control {
            width:100%;
            padding:11px 14px 11px 38px;
            border:1px solid #E5E7EB;
            border-radius:10px;
            font-family:'Outfit',sans-serif;
            font-size:0.9rem;
            transition:border 0.2s, box-shadow 0.2s;
            color:#111827;
        }
        .form-control:focus { outline:none; border-color:#FF6B35; box-shadow:0 0 0 3px rgba(255,107,53,0.1); }
        .form-control.is-invalid { border-color:#EF4444; }

        .check-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; }
        .check-label { display:flex; align-items:center; gap:8px; font-size:0.85rem; color:#374151; cursor:pointer; }
        .check-label input { accent-color:#FF6B35; width:15px; height:15px; }
        .forgot-link { font-size:0.82rem; color:#FF6B35; text-decoration:none; font-weight:600; }
        .forgot-link:hover { text-decoration:underline; }

        .btn-submit {
            width:100%;
            padding:13px;
            background:#FF6B35;
            color:white;
            border:none;
            border-radius:10px;
            font-family:'Outfit',sans-serif;
            font-size:0.95rem;
            font-weight:700;
            cursor:pointer;
            transition:background 0.2s, transform 0.15s;
            display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .btn-submit:hover { background:#E85520; transform:translateY(-1px); }
        .btn-submit:active { transform:translateY(0); }

        .alert-danger {
            background:#FEF2F2;
            color:#991B1B;
            border:1px solid #FECACA;
            border-radius:10px;
            padding:12px 16px;
            font-size:0.85rem;
            margin-bottom:18px;
        }
        .alert-success {
            background:#ECFDF5;
            color:#065F46;
            border:1px solid #A7F3D0;
            border-radius:10px;
            padding:12px 16px;
            font-size:0.85rem;
            margin-bottom:18px;
        }
        .error-msg { color:#EF4444; font-size:0.78rem; margin-top:4px; }

        @media (max-width:480px) {
            .auth-card { padding:32px 24px; }
        }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-logo">
        @if($adminSettings['site_logo'])
            <img src="{{ asset('uploads/settings/' . $adminSettings['site_logo']) }}" alt="{{ $adminSettings['site_name'] }}"><br>
        @else
            <div class="auth-logo-icon"><i class="fas fa-paw"></i></div><br>
        @endif
        <h1>Admin Login</h1>
        <p>{{ $adminSettings['site_name'] }} — Control Panel</p>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if($errors->has('email') && !$errors->has('password'))
        <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}</div>
    @endif

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
            </div>
            @error('email') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       required placeholder="••••••••">
            </div>
            @error('password') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="check-row">
            <label class="check-label">
                <input type="checkbox" name="remember" value="1">
                Remember me
            </label>
            <a href="{{ route('admin.password.request') }}" class="forgot-link">Forgot password?</a>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-sign-in-alt"></i> Sign In
        </button>
    </form>
</div>
</body>
</html>
