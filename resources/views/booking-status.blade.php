<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status – TDA COURT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink: #0f0f14; --muted: #6b6b80; --line: #e4e4ef;
            --surface: #f7f7fb; --white: #fff;
            --accent: #4f46e5; --accent-dark: #3730a3; --accent-light: #eef2ff;
            --green: #059669; --green-light: #ecfdf5;
            --amber: #d97706; --amber-light: #fffbeb;
            --red: #dc2626; --red-light: #fef2f2;
        }
        body { font-family: 'Inter', sans-serif; background: var(--surface); color: var(--ink); min-height: 100vh; }

        nav { background: var(--white); border-bottom: 1px solid var(--line); padding: 0 48px; height: 64px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
        .nav-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 20px; letter-spacing: 3px; color: var(--ink); text-decoration: none; }
        .nav-logo span { color: var(--accent); }
        .nav-back { font-size: 14px; color: var(--muted); text-decoration: none; font-weight: 500; transition: color .15s; }
        .nav-back:hover { color: var(--ink); }

        .wrap { max-width: 560px; margin: 0 auto; padding: 48px 20px 80px; }

        /* STATUS HEADER */
        .status-header { text-align: center; margin-bottom: 28px; }
        .status-icon { font-size: 56px; margin-bottom: 12px; }
        .status-title { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; margin-bottom: 6px; }
        .status-sub { font-size: 14px; color: var(--muted); }

        /* STATUS BADGE */
        .status-badge-wrap { display: flex; justify-content: center; margin-bottom: 24px; }
        .badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 700; }
        .badge-pending   { background: var(--amber-light); color: var(--amber); }
        .badge-confirmed { background: var(--green-light); color: var(--green); }
        .badge-cancelled { background: var(--red-light);   color: var(--red); }
        .badge-completed { background: var(--accent-light); color: var(--accent); }

        /* REF BOX */
        .ref-box { background: var(--accent-light); border: 1.5px solid #c7d2fe; border-radius: 14px; padding: 18px 20px; margin-bottom: 20px; text-align: center; }
        .ref-label { font-size: 11px; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
        .ref-code { font-family: 'Syne', sans-serif; font-size: 24px; font-weight: 800; letter-spacing: 4px; color: var(--ink); }

        /* DETAIL CARD */
        .card { background: var(--white); border: 1px solid var(--line); border-radius: 14px; overflow: hidden; margin-bottom: 16px; }
        .card-head { padding: 14px 18px; border-bottom: 1px solid var(--line); font-size: 13px; font-weight: 700; color: var(--ink); background: var(--surface); }
        .card-body { padding: 4px 0; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 11px 18px; border-bottom: 1px solid var(--line); font-size: 13px; gap: 12px; }
        .info-row:last-child { border-bottom: none; }
        .info-key { color: var(--muted); font-weight: 500; flex-shrink: 0; }
        .info-val { font-weight: 600; text-align: right; word-break: break-word; }

        /* TIMELINE */
        .timeline { margin-bottom: 20px; }
        .timeline-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); margin-bottom: 12px; }
        .timeline-steps { display: flex; gap: 0; }
        .t-step { flex: 1; text-align: center; position: relative; }
        .t-step::before { content: ''; position: absolute; top: 14px; left: 50%; right: -50%; height: 2px; background: var(--line); z-index: 0; }
        .t-step:last-child::before { display: none; }
        .t-dot { width: 28px; height: 28px; border-radius: 50%; margin: 0 auto 6px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; position: relative; z-index: 1; border: 2px solid var(--line); background: var(--white); color: var(--muted); }
        .t-dot.done { background: var(--green); border-color: var(--green); color: #fff; }
        .t-dot.current { background: var(--accent); border-color: var(--accent); color: #fff; }
        .t-label { font-size: 11px; color: var(--muted); font-weight: 500; }
        .t-label.done { color: var(--green); font-weight: 600; }
        .t-label.current { color: var(--accent); font-weight: 600; }

        /* PAYMENT STATUS */
        .pay-status { display: flex; align-items: center; justify-content: space-between; background: var(--white); border: 1px solid var(--line); border-radius: 14px; padding: 16px 18px; margin-bottom: 16px; }
        .pay-left { font-size: 13px; }
        .pay-method { font-weight: 700; margin-bottom: 2px; }
        .pay-sub { color: var(--muted); font-size: 12px; }
        .pay-amount { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; color: var(--green); }

        /* ACTIONS */
        .actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-primary { flex: 1; padding: 12px 20px; border-radius: 10px; background: var(--accent); color: #fff; font-size: 14px; font-weight: 700; text-decoration: none; text-align: center; transition: background .15s; min-width: 140px; }
        .btn-primary:hover { background: var(--accent-dark); }
        .btn-outline { flex: 1; padding: 12px 20px; border-radius: 10px; border: 1.5px solid var(--line); color: var(--muted); font-size: 14px; font-weight: 600; text-decoration: none; text-align: center; transition: all .15s; min-width: 140px; }
        .btn-outline:hover { border-color: var(--ink); color: var(--ink); }

        /* CANCELLED NOTICE */
        .cancelled-notice { background: var(--red-light); border: 1.5px solid #fca5a5; border-radius: 12px; padding: 14px 16px; font-size: 13px; color: var(--red); margin-bottom: 16px; display: flex; gap: 10px; align-items: flex-start; line-height: 1.5; }

        @media (max-width: 500px) {
            nav { padding: 0 16px; }
            .wrap { padding: 28px 16px 60px; }
            .actions { flex-direction: column; }
            .ref-code { font-size: 18px; }
        }
    </style>
</head>
<body>

<nav>
    <a href="/" class="nav-logo">TDA<span>COURT</span></a>
    <a href="/" class="nav-back">← Back to home</a>
</nav>

<div class="wrap">

    <!-- STATUS HEADER -->
    <div class="status-header">
        @if($reservation->status === 'confirmed')
            <div class="status-icon">✅</div>
            <div class="status-title">Booking Confirmed!</div>
            <div class="status-sub">Your court is reserved and ready. See you on the court!</div>
        @elseif($reservation->status === 'completed')
            <div class="status-icon">🏁</div>
            <div class="status-title">Session Completed</div>
            <div class="status-sub">Thank you for playing with us. We hope to see you again!</div>
        @elseif($reservation->status === 'cancelled')
            <div class="status-icon">❌</div>
            <div class="status-title">Booking Cancelled</div>
            <div class="status-sub">This reservation has been cancelled.</div>
        @else
            <div class="status-icon">⏳</div>
            <div class="status-title">Booking Pending</div>
            <div class="status-sub">Your booking is under review. We'll confirm it shortly.</div>
        @endif
    </div>

    <!-- STATUS BADGE -->
    <div class="status-badge-wrap">
        <span class="badge badge-{{ $reservation->status }}">
            @if($reservation->status === 'confirmed') ✅ Confirmed
            @elseif($reservation->status === 'completed') 🏁 Completed
            @elseif($reservation->status === 'cancelled') ❌ Cancelled
            @else ⏳ Pending
            @endif
        </span>
    </div>

    <!-- CANCELLED NOTICE -->
    @if($reservation->status === 'cancelled')
    <div class="cancelled-notice">
        ⚠️ This booking has been cancelled. If you believe this is a mistake, please contact us at <strong>hello@wolfpax.com</strong> or call <strong>0966 615 4780</strong>.
    </div>
    @endif

    <!-- REFERENCE -->
    <div class="ref-box">
        <div class="ref-label">Booking Reference</div>
        <div class="ref-code">{{ $reservation->reference_number }}</div>
    </div>

    <!-- TIMELINE -->
    <div class="timeline">
        <div class="timeline-title">Booking Progress</div>
        <div class="timeline-steps">
            @php
                $steps = ['pending', 'confirmed', 'completed'];
                $currentIndex = array_search($reservation->status, $steps);
                if ($reservation->status === 'cancelled') $currentIndex = -1;
            @endphp
            <div class="t-step">
                <div class="t-dot {{ $currentIndex >= 0 ? 'done' : '' }}">✓</div>
                <div class="t-label {{ $currentIndex >= 0 ? 'done' : '' }}">Booked</div>
            </div>
            <div class="t-step">
                <div class="t-dot {{ $currentIndex >= 1 ? 'done' : ($currentIndex === 0 ? 'current' : '') }}">
                    {{ $currentIndex >= 1 ? '✓' : '2' }}
                </div>
                <div class="t-label {{ $currentIndex >= 1 ? 'done' : ($currentIndex === 0 ? 'current' : '') }}">Confirmed</div>
            </div>
            <div class="t-step">
                <div class="t-dot {{ $currentIndex >= 2 ? 'done' : ($currentIndex === 1 ? 'current' : '') }}">
                    {{ $currentIndex >= 2 ? '✓' : '3' }}
                </div>
                <div class="t-label {{ $currentIndex >= 2 ? 'done' : ($currentIndex === 1 ? 'current' : '') }}">Completed</div>
            </div>
        </div>
    </div>

    <!-- BOOKING DETAILS -->
    <div class="card">
        <div class="card-head">📅 Booking Details</div>
        <div class="card-body">
            <div class="info-row"><span class="info-key">Name</span><span class="info-val">{{ $reservation->full_name }}</span></div>
            <div class="info-row"><span class="info-key">Mobile</span><span class="info-val">{{ $reservation->mobile_number }}</span></div>
            <div class="info-row"><span class="info-key">Email</span><span class="info-val">{{ $reservation->email }}</span></div>
            <div class="info-row"><span class="info-key">Court</span><span class="info-val">{{ $reservation->court_name }}</span></div>
            <div class="info-row"><span class="info-key">Date</span><span class="info-val">{{ $reservation->booking_date->format('l, F j, Y') }}</span></div>
            <div class="info-row"><span class="info-key">Time Slot</span><span class="info-val">{{ $reservation->time_slot }}</span></div>
        </div>
    </div>

    <!-- PAYMENT -->
    <div class="pay-status">
        <div class="pay-left">
            <div class="pay-method">{{ $reservation->payment_method }}</div>
            <div class="pay-sub">
                Payment:
                @if($reservation->payment_status === 'paid')
                    <span style="color:var(--green);font-weight:600">✅ Paid</span>
                @else
                    <span style="color:var(--amber);font-weight:600">⏳ Pending payment</span>
                @endif
            </div>
        </div>
        <div class="pay-amount">₱{{ number_format($reservation->amount) }}</div>
    </div>

    <!-- ACTIONS -->
    <div class="actions">
        <a href="/" class="btn-primary">← Back to home</a>
        <a href="/book" class="btn-outline">Book another court</a>
    </div>

</div>
</body>
</html>