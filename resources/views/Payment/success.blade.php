<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful – WOLFPAX</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --ink: #0f0f14; --muted: #6b6b80; --line: #e4e4ef; --surface: #f7f7fb; --white: #fff; --accent: #4f46e5; --green: #059669; --green-light: #ecfdf5; }
        body { font-family: 'Inter', sans-serif; background: var(--surface); min-height: 100vh; display: flex; flex-direction: column; }
        nav { background: var(--white); border-bottom: 1px solid var(--line); padding: 0 48px; height: 64px; display: flex; align-items: center; }
        .nav-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 20px; letter-spacing: 3px; color: var(--ink); text-decoration: none; }
        .nav-logo span { color: var(--accent); }
        .wrap { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .card { background: var(--white); border-radius: 20px; border: 1px solid var(--line); padding: 40px 36px; max-width: 500px; width: 100%; text-align: center; box-shadow: 0 4px 32px rgba(0,0,0,.06); }
        .success-circle { width: 80px; height: 80px; border-radius: 50%; background: var(--green-light); display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 20px; }
        .title { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; color: var(--ink); margin-bottom: 8px; }
        .sub { font-size: 14px; color: var(--muted); margin-bottom: 28px; line-height: 1.6; }
        .ref-box { background: #ecfdf5; border: 1.5px solid #6ee7b7; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; }
        .ref-label { font-size: 11px; font-weight: 700; color: var(--green); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
        .ref-code { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; letter-spacing: 4px; color: var(--ink); }
        .detail-row { display: flex; justify-content: space-between; font-size: 13px; padding: 9px 0; border-bottom: 1px solid var(--line); }
        .detail-row:last-child { border-bottom: none; }
        .detail-key { color: var(--muted); }
        .detail-val { font-weight: 600; }
        .paid-badge { display: inline-flex; align-items: center; gap: 6px; background: var(--green-light); color: var(--green); font-size: 13px; font-weight: 700; padding: 6px 16px; border-radius: 20px; margin-bottom: 24px; }
        .details-box { background: var(--surface); border-radius: 12px; padding: 16px; margin-bottom: 24px; text-align: left; }
        .btn { display: block; width: 100%; padding: 13px; border-radius: 10px; background: var(--accent); color: #fff; font-size: 14px; font-weight: 700; text-decoration: none; text-align: center; margin-bottom: 10px; }
        .btn-outline { display: block; width: 100%; padding: 13px; border-radius: 10px; border: 1.5px solid var(--line); color: var(--muted); font-size: 14px; font-weight: 600; text-decoration: none; text-align: center; }
        @media (max-width: 500px) { nav { padding: 0 16px; } .card { padding: 28px 18px; } }
    </style>
</head>
<body>
<nav><a href="/" class="nav-logo">WOLF<span>PAX</span></a></nav>
<div class="wrap">
    <div class="card">
        <div class="success-circle">✅</div>
        <div class="title">Payment Successful!</div>
        <div class="sub">Your GCash payment has been processed and your court is now confirmed. A confirmation email has been sent to <strong>{{ $reservation->email }}</strong>.</div>

        <div class="paid-badge">💚 GCash Payment Verified</div>

        <div class="ref-box">
            <div class="ref-label">Booking Reference</div>
            <div class="ref-code">{{ $reservation->reference_number }}</div>
        </div>

        <div class="details-box">
            <div class="detail-row"><span class="detail-key">Court</span><span class="detail-val">{{ $reservation->court_name }}</span></div>
            <div class="detail-row"><span class="detail-key">Date</span><span class="detail-val">{{ $reservation->booking_date->format('M d, Y') }}</span></div>
            <div class="detail-row"><span class="detail-key">Slots</span><span class="detail-val">{{ $reservation->time_slots_display }}</span></div>
            <div class="detail-row"><span class="detail-key">Amount Paid</span><span class="detail-val" style="color:var(--green)">₱{{ number_format($reservation->amount) }}</span></div>
            <div class="detail-row"><span class="detail-key">Paid via</span><span class="detail-val">GCash 💚</span></div>
        </div>

        <a href="{{ route('reservations.show', $reservation->reference_number) }}" class="btn">View Booking Status →</a>
        <a href="/" class="btn-outline">← Back to home</a>
    </div>
</div>
</body>
</html>