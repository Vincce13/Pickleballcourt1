<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed – WOLFPAX</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --ink: #0f0f14; --muted: #6b6b80; --line: #e4e4ef; --surface: #f7f7fb; --white: #fff; --accent: #4f46e5; --red: #dc2626; --red-light: #fef2f2; }
        body { font-family: 'Inter', sans-serif; background: var(--surface); min-height: 100vh; display: flex; flex-direction: column; }
        nav { background: var(--white); border-bottom: 1px solid var(--line); padding: 0 48px; height: 64px; display: flex; align-items: center; }
        .nav-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 20px; letter-spacing: 3px; color: var(--ink); text-decoration: none; }
        .nav-logo span { color: var(--accent); }
        .wrap { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .card { background: var(--white); border-radius: 20px; border: 1px solid var(--line); padding: 40px 36px; max-width: 480px; width: 100%; text-align: center; }
        .fail-circle { width: 80px; height: 80px; border-radius: 50%; background: var(--red-light); display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 20px; }
        .title { font-family: 'Syne', sans-serif; font-size: 24px; font-weight: 800; color: var(--ink); margin-bottom: 8px; }
        .sub { font-size: 14px; color: var(--muted); margin-bottom: 28px; line-height: 1.6; }
        .notice { background: var(--red-light); border: 1.5px solid #fca5a5; border-radius: 12px; padding: 14px 16px; font-size: 13px; color: var(--red); margin-bottom: 24px; text-align: left; line-height: 1.6; }
        .ref-box { background: var(--surface); border-radius: 12px; padding: 14px; margin-bottom: 24px; }
        .ref-label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:4px; }
        .ref-code { font-family:'Syne',sans-serif; font-size:18px; font-weight:800; letter-spacing:3px; }
        .btn { display:block; width:100%; padding:13px; border-radius:10px; background:var(--accent); color:#fff; font-size:14px; font-weight:700; text-decoration:none; text-align:center; margin-bottom:10px; }
        .btn-gcash { display:block; width:100%; padding:13px; border-radius:10px; background:#007bff; color:#fff; font-size:14px; font-weight:700; text-decoration:none; text-align:center; margin-bottom:10px; }
        .btn-outline { display:block; width:100%; padding:13px; border-radius:10px; border:1.5px solid var(--line); color:var(--muted); font-size:14px; font-weight:600; text-decoration:none; text-align:center; }
        @media (max-width:500px) { nav { padding:0 16px; } .card { padding:28px 18px; } }
    </style>
</head>
<body>
<nav><a href="/" class="nav-logo">WOLF<span>PAX</span></a></nav>
<div class="wrap">
    <div class="card">
        <div class="fail-circle">❌</div>
        <div class="title">Payment Failed</div>
        <div class="sub">Your GCash payment was not completed. Don't worry — your booking is still reserved for now.</div>

        <div class="notice">
            ⚠️ Your booking <strong>{{ $reservation->reference_number }}</strong> is still <strong>pending</strong>.
            You can retry your GCash payment below or choose to pay at the counter when you arrive.
        </div>

        <div class="ref-box">
            <div class="ref-label">Booking Reference</div>
            <div class="ref-code">{{ $reservation->reference_number }}</div>
        </div>

        <!-- Retry GCash -->
        <form method="POST" action="{{ route('payment.gcash', $reservation->reference_number) }}">
            @csrf
            <button type="submit" class="btn-gcash">💚 Retry GCash Payment</button>
        </form>

        <a href="{{ route('reservations.show', $reservation->reference_number) }}" class="btn">View Booking Status</a>
        <a href="/" class="btn-outline">← Back to home</a>
    </div>
</div>
</body>
</html>