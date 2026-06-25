<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – TDA COURT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink: #0f0f14; --muted: #6b6b80; --line: #e4e4ef;
            --surface: #f7f7fb; --white: #fff;
            --accent: #4f46e5; --accent-dark: #3730a3; --accent-light: #eef2ff;
            --danger: #ef4444; --danger-light: #fef2f2;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f0f14;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }

        .login-wrap {
            width: 100%; max-width: 420px;
        }

        /* LOGO */
        .logo {
            text-align: center; margin-bottom: 32px;
        }
        .logo-text {
            font-family: 'Syne', sans-serif; font-weight: 800;
            font-size: 28px; letter-spacing: 4px; color: #fff;
        }
        .logo-text span { color: #818cf8; }
        .logo-sub {
            font-size: 13px; color: rgba(255,255,255,.4);
            margin-top: 6px; letter-spacing: 2px; text-transform: uppercase;
        }

        /* CARD */
        .card {
            background: var(--white);
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 24px 64px rgba(0,0,0,.4);
        }
        .card-title { font-size: 20px; font-weight: 800; color: var(--ink); margin-bottom: 4px; font-family: 'Syne', sans-serif; }
        .card-sub { font-size: 13px; color: var(--muted); margin-bottom: 28px; }

        /* ALERTS */
        .alert-error {
            background: var(--danger-light); border: 1.5px solid #fca5a5;
            border-radius: 10px; padding: 12px 14px;
            font-size: 13px; color: #991b1b;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-info {
            background: var(--accent-light); border: 1.5px solid #c7d2fe;
            border-radius: 10px; padding: 12px 14px;
            font-size: 13px; color: var(--accent);
            margin-bottom: 20px;
        }

        /* FIELDS */
        .field { margin-bottom: 18px; }
        .field label { display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
        .field input {
            width: 100%; padding: 12px 14px;
            border: 1.5px solid var(--line); border-radius: 10px;
            font-size: 15px; font-family: 'Inter', sans-serif;
            background: var(--surface); color: var(--ink);
            outline: none; transition: border-color .15s;
            -webkit-appearance: none;
        }
        .field input:focus { border-color: var(--accent); background: var(--white); }
        .field input.error { border-color: var(--danger); background: var(--danger-light); }
        .field-error { font-size: 12px; color: var(--danger); margin-top: 5px; }

        /* REMEMBER */
        .remember-row {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 24px; font-size: 13px; color: var(--muted);
            cursor: pointer;
        }
        .remember-row input { width: auto; accent-color: var(--accent); cursor: pointer; }

        /* BUTTON */
        .btn-login {
            width: 100%; padding: 14px;
            border-radius: 12px; border: none;
            background: var(--accent); color: #fff;
            font-size: 15px; font-weight: 700;
            cursor: pointer; font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 16px rgba(79,70,229,.35);
            transition: background .15s, transform .15s;
        }
        .btn-login:hover { background: var(--accent-dark); transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }

        /* FOOTER */
        .login-footer {
            text-align: center; margin-top: 24px;
            font-size: 12px; color: rgba(255,255,255,.3);
        }
        .login-footer a { color: rgba(255,255,255,.5); text-decoration: none; }
        .login-footer a:hover { color: #fff; }

        /* SHOW/HIDE PASSWORD */
        .field-pw { position: relative; }
        .field-pw input { padding-right: 44px; }
        .pw-toggle {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            font-size: 16px; color: var(--muted); padding: 4px;
            line-height: 1;
        }

        @media (max-width: 480px) {
            .card { padding: 28px 20px; }
        }
    </style>
</head>
<body>

<div class="login-wrap">

    <!-- LOGO -->
    <div class="logo">
        <div class="logo-text">SZAM<span>COURT</span></div>
        <div class="logo-sub">Admin Panel</div>
    </div>

    <!-- CARD -->
    <div class="card">
        <div class="card-title">Welcome back 👋</div>
        <div class="card-sub">Sign in to manage court reservations.</div>

        {{-- Error from session --}}
        @if(session('error'))
        <div class="alert-error">🔒 {{ session('error') }}</div>
        @endif

        {{-- Validation errors --}}
        @if($errors->any())
        <div class="alert-error">
            ⚠️ {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="field">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="admin@szam.com"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    class="{{ $errors->has('email') ? 'error' : '' }}"
                    required
                    autofocus
                >
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="field-pw">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        class="{{ $errors->has('password') ? 'error' : '' }}"
                        required
                    >
                    <button type="button" class="pw-toggle" onclick="togglePw()" id="pwToggle" title="Show/hide password">👁</button>
                </div>
            </div>

            <label class="remember-row">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Keep me signed in
            </label>

            <button type="submit" class="btn-login">Sign in to Admin Panel →</button>
        </form>
    </div>

    <div class="login-footer">
        <a href="/">← Back to SZAM site</a>
    </div>

</div>

<script>
    function togglePw() {
        const input  = document.getElementById('password');
        const toggle = document.getElementById('pwToggle');
        if (input.type === 'password') {
            input.type = 'text';
            toggle.textContent = '🙈';
        } else {
            input.type = 'password';
            toggle.textContent = '👁';
        }
    }
</script>
</body>
</html>