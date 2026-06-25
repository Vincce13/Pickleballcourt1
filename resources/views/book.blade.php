<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Dark mode — must be first to prevent flash -->
<script>
    if (localStorage.getItem('tdaDark') === '1' ||
       (localStorage.getItem('tdaDark') === null &&
        window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }
</script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Book a Court – TDA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink:#0f0f14;--ink2:#2a2a38;--muted:#6b6b80;--line:#e4e4ef;
            --surface:#f7f7fb;--white:#fff;--accent:#4f46e5;--accent-dark:#3730a3;
            --accent-light:#eef2ff;--danger:#ef4444;--danger-light:#fef2f2;
            --green:#059669;--green-light:#ecfdf5;
            --amber:#d97706;--amber-light:#fffbeb;
        }
        body { font-family:'Inter',sans-serif; background:var(--surface); color:var(--ink); min-height:100vh; overflow-x:hidden; }
        nav { background:rgba(255,255,255,.96); backdrop-filter:blur(12px); border-bottom:1px solid var(--line); padding:0 48px; height:64px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:100; }
        .nav-logo { font-family:'Syne',sans-serif; font-weight:800; font-size:20px; letter-spacing:3px; color:var(--ink); text-decoration:none; }
        .nav-logo span { color:var(--accent); }
        .nav-back { font-size:14px; color:var(--muted); text-decoration:none; display:flex; align-items:center; gap:6px; font-weight:500; }
        .nav-back:hover { color:var(--ink); }
        .page-wrap { max-width:600px; margin:0 auto; padding:40px 20px 80px; }
        .card { background:var(--white); border-radius:20px; border:1px solid var(--line); padding:32px 36px; box-shadow:0 2px 24px rgba(0,0,0,.05); }
        .card-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px; gap:12px; }
        .card-title { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:var(--ink); }
        .wolfpax-logo { font-family:'Syne',sans-serif; font-weight:800; font-size:15px; letter-spacing:2px; color:var(--ink); flex-shrink:0; }
        .wolfpax-logo span { color:var(--accent); }
        .card-sub { font-size:13px; color:var(--muted); margin-bottom:24px; }
        /* STEPS */
        .steps { display:flex; align-items:center; margin-bottom:28px; }
        .step-item { display:flex; align-items:center; gap:6px; }
        .step-bubble { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; flex-shrink:0; transition:all .2s; }
        .step-bubble.active   { background:var(--accent); color:#fff; }
        .step-bubble.done     { background:var(--accent); color:#fff; }
        .step-bubble.inactive { background:transparent; color:var(--muted); border:2px solid var(--line); }
        .step-label { font-size:12px; font-weight:600; white-space:nowrap; }
        .step-label.active   { color:var(--accent); }
        .step-label.done     { color:var(--accent); }
        .step-label.inactive { color:var(--muted); }
        .step-connector { flex:1; height:2px; margin:0 8px; background:var(--line); min-width:16px; transition:background .2s; }
        .step-connector.done { background:var(--accent); }
        hr.divider { border:none; border-top:1px solid var(--line); margin:24px 0; }
        .step-panel { display:none; }
        .step-panel.active { display:block; }
        .section-heading { font-size:16px; font-weight:700; color:var(--ink); margin-bottom:4px; }
        .section-sub { font-size:13px; color:var(--muted); margin-bottom:20px; }
        /* FIELDS */
        .field { margin-bottom:18px; }
        .field label { display:block; font-size:13px; font-weight:600; color:var(--ink); margin-bottom:6px; }
        .field label .req { color:var(--danger); margin-left:2px; }
        .field input { width:100%; padding:12px 14px; border:1.5px solid var(--line); border-radius:10px; font-size:15px; font-family:'Inter',sans-serif; background:var(--surface); color:var(--ink); outline:none; transition:border-color .15s; -webkit-appearance:none; }
        .field input:focus { border-color:var(--accent); background:var(--white); }
        .field input.error { border-color:var(--danger); background:var(--danger-light); }
        .field-error { font-size:12px; color:var(--danger); margin-top:5px; display:none; }
        .field-error.show { display:block; }
        /* COURT PICKER */
        .court-options { display:flex; flex-direction:column; gap:10px; margin-bottom:20px; }
        .court-option { display:flex; align-items:center; gap:12px; padding:14px; border-radius:12px; border:1.5px solid var(--line); background:var(--surface); cursor:pointer; transition:all .15s; -webkit-tap-highlight-color:transparent; }
        .court-option:hover { border-color:#c7d2fe; background:var(--accent-light); }
        .court-option.selected { border-color:var(--accent); background:var(--accent-light); }
        .court-option input[type=radio] { display:none; }
        .court-dot { width:18px; height:18px; border-radius:50%; border:2px solid var(--line); flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:all .15s; }
        .court-option.selected .court-dot { border-color:var(--accent); background:var(--accent); }
        .court-option.selected .court-dot::after { content:''; width:6px; height:6px; border-radius:50%; background:#fff; }
        .court-emoji { font-size:20px; flex-shrink:0; }
        .court-info-text { flex:1; min-width:0; }
        .court-info-name { font-weight:600; font-size:14px; color:var(--ink); }
        .court-info-desc { font-size:12px; color:var(--muted); margin-top:2px; }
        .court-price-tag { font-weight:700; color:var(--accent); font-size:14px; flex-shrink:0; }
        /* DATE */
        .date-field input[type=date] { width:100%; padding:12px 14px; border:1.5px solid var(--line); border-radius:10px; font-size:15px; font-family:'Inter',sans-serif; background:var(--surface); color:var(--ink); outline:none; transition:border-color .15s; cursor:pointer; -webkit-appearance:none; }
        .date-field input[type=date]:focus { border-color:var(--accent); }
        /* SLOTS */
        .time-section-label { display:flex; align-items:center; gap:8px; font-size:13px; font-weight:700; color:var(--ink); margin-bottom:10px; margin-top:18px; }
        .time-badge { padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
        .time-badge.am { background:#fef9c3; color:#854d0e; }
        .time-badge.pm { background:#ede9fe; color:#5b21b6; }
        .slots-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:6px; }
        .slot { padding:10px 4px; border-radius:9px; border:1.5px solid var(--line); background:var(--surface); text-align:center; font-size:11px; font-weight:500; cursor:pointer; transition:all .15s; color:var(--ink); user-select:none; -webkit-tap-highlight-color:transparent; line-height:1.3; position:relative; }
        .slot:hover:not(.booked):not(.loading) { border-color:#c7d2fe; background:var(--accent-light); color:var(--accent); }
        .slot.selected { border-color:var(--accent); background:var(--accent); color:#fff; font-weight:600; }
        /* BOOKED — red styling so it's clearly unavailable */
        .slot.booked { background:var(--danger-light); color:var(--danger); text-decoration:line-through; cursor:not-allowed; border-color:#fca5a5; font-weight:600; }
        .slot.loading { background:#f3f4f6; color:#d1d5db; cursor:wait; border-color:#f3f4f6; }
        /* SELECTED COUNT PILL */
        .selected-count { display:none; align-items:center; gap:8px; background:var(--accent-light); border:1.5px solid #c7d2fe; border-radius:10px; padding:10px 14px; margin-top:12px; font-size:13px; color:var(--accent); font-weight:600; }
        .selected-count.show { display:flex; }
        .selected-count-num { font-family:'Syne',sans-serif; font-size:18px; font-weight:800; }
        .slot-legend { display:flex; gap:14px; margin-top:10px; flex-wrap:wrap; }
        .legend-item { display:flex; align-items:center; gap:5px; font-size:11px; color:var(--muted); }
        .legend-dot { width:10px; height:10px; border-radius:3px; flex-shrink:0; }
        /* FOOTER */
        .card-footer { display:flex; justify-content:space-between; align-items:center; margin-top:28px; gap:12px; }
        .btn-back { padding:12px 20px; border-radius:10px; border:1.5px solid var(--line); background:transparent; font-size:14px; font-weight:600; color:var(--muted); cursor:pointer; transition:all .15s; font-family:'Inter',sans-serif; }
        .btn-back:hover { border-color:var(--ink); color:var(--ink); }
        .btn-next { flex:1; padding:13px; border-radius:10px; border:none; background:var(--accent); color:#fff; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(79,70,229,.3); transition:background .15s; font-family:'Inter',sans-serif; }
        .btn-next:hover { background:var(--accent-dark); }
        .btn-next:disabled { background:#a5b4fc; cursor:not-allowed; box-shadow:none; }
        /* SUMMARY */
        .summary-box { background:var(--surface); border:1.5px solid var(--line); border-radius:14px; padding:18px; margin-bottom:20px; }
        .summary-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; color:var(--muted); margin-bottom:14px; }
        .summary-row { display:flex; justify-content:space-between; font-size:13px; margin-bottom:9px; gap:8px; }
        .summary-key { color:var(--muted); flex-shrink:0; }
        .summary-val { font-weight:600; text-align:right; word-break:break-word; }
        .summary-slots { display:flex; flex-wrap:wrap; gap:5px; justify-content:flex-end; }
        .summary-slot-tag { background:var(--accent-light); color:var(--accent); font-size:11px; font-weight:600; padding:3px 8px; border-radius:6px; }
        .summary-total { border-top:1px solid var(--line); padding-top:12px; margin-top:12px; display:flex; justify-content:space-between; font-size:15px; font-weight:700; }
        .summary-total .amount { color:var(--accent); font-size:18px; }
        /* PAYMENT */
        .payment-options { display:flex; flex-direction:column; gap:10px; margin-bottom:20px; }
        .pay-option { display:flex; align-items:center; gap:12px; padding:13px 14px; border-radius:12px; border:1.5px solid var(--line); background:var(--surface); cursor:pointer; transition:all .15s; }
        .pay-option:hover { border-color:#c7d2fe; background:var(--accent-light); }
        .pay-option.selected { border-color:var(--accent); background:var(--accent-light); }
        .pay-option input[type=radio] { display:none; }
        .pay-dot { width:18px; height:18px; border-radius:50%; border:2px solid var(--line); flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:all .15s; }
        .pay-option.selected .pay-dot { border-color:var(--accent); background:var(--accent); }
        .pay-option.selected .pay-dot::after { content:''; width:6px; height:6px; border-radius:50%; background:#fff; }
        .pay-emoji { font-size:20px; }
        .pay-name { font-size:14px; font-weight:600; color:var(--ink); }
        /* LOADING OVERLAY */
        .slots-loading { display:none; text-align:center; padding:20px; font-size:13px; color:var(--muted); }
        .slots-loading.show { display:block; }
        /* RESPONSIVE */
        @media (max-width:600px) {
            nav { padding:0 16px; }
            .page-wrap { padding:24px 12px 60px; }
            .card { padding:22px 18px; }
            .steps { gap:0; }
            .step-label { display:none; }
            .step-connector { min-width:12px; margin:0 6px; }
            .slots-grid { grid-template-columns:repeat(2,1fr); gap:7px; }
            .slot { font-size:10px; padding:10px 3px; }
            .card-footer { flex-direction:column-reverse; }
            .btn-next, .btn-back { width:100%; text-align:center; }
        }

        html.dark {
    --ink:#e8e8f5; --ink2:#1a1a2e; --muted:#9ca3af;
    --line:#2a2a3e; --surface:#0d0d1a; --white:#1c1c2e;
    --accent:#818cf8; --accent-dark:#6366f1; --accent-light:#1e1b4b;
    --green:#34d399; --green-light:#064e3b;
    --amber:#fbbf24; --amber-light:#451a03;
    --red:#f87171; --red-light:#450a0a;
    --danger:#ef4444; --danger-light:#450a0a;
}
html.dark body { background:var(--surface); color:var(--ink); }
html.dark nav  { background:rgba(28,28,46,.97) !important; border-bottom-color:var(--line); }
html.dark .card, html.dark .summary-box { background:var(--white); border-color:var(--line); }
html.dark input, html.dark select, html.dark textarea {
    background:var(--surface) !important; color:var(--ink) !important; border-color:var(--line) !important;
}
html.dark input:focus { border-color:var(--accent) !important; }
html.dark .court-option, html.dark .pay-option, html.dark .court-option:hover { background:var(--surface); }
html.dark .court-option.selected, html.dark .pay-option.selected { background:var(--accent-light); }
html.dark .slot { background:var(--surface); color:var(--ink); }
html.dark .slot.booked { background:#450a0a; color:#f87171; border-color:#7f1d1d; }
html.dark .gcash-number-box { background:#1c1c2e; }
html.dark .gcash-num { color:var(--ink); }
html.dark .gcash-account-name { color:var(--muted); }
html.dark .ref-box { background:var(--accent-light); border-color:#3730a3; }
html.dark .receipt-section { background:var(--white); border-color:var(--line); }
html.dark .receipt-head { background:var(--surface); }
html.dark .dropzone { background:var(--surface); border-color:var(--line); }
html.dark .dropzone:hover { background:var(--accent-light); border-color:var(--accent); }
html.dark .pending-notice { background:var(--amber-light); color:#fbbf24; }
html.dark .paid-notice { background:var(--green-light); color:#34d399; }
html.dark .booking-card { background:var(--white); border-color:var(--line); }

/* Add these styles to book.blade.php <style> block */

/* LOCKED slot — shown when someone else holds the lock */
.slot.locked {
    background: #fffbeb;
    color: #92400e;
    border-color: #fcd34d;
    cursor: not-allowed;
    font-size: 10px;
    line-height: 1.3;
}
html.dark .slot.locked {
    background: #451a03;
    color: #fbbf24;
    border-color: #78350f;
}

/* AVAILABLE slot — clear green tint on hover */
.slot.available:hover {
    border-color: var(--accent);
    background: var(--accent-light);
    color: var(--accent);
    transform: scale(1.03);
}

/* Countdown text inside selected slot */
.slot-countdown {
    display: block;
    font-size: 9px;
    margin-top: 2px;
    opacity: .85;
    font-weight: 700;
}

/* Slot timer banner */
@keyframes slideDown {
    from { opacity:0; transform:translateY(-10px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes slideUp {
    from { opacity:0; transform:translate(-50%, 10px); }
    to   { opacity:1; transform:translate(-50%, 0); }
}
    </style>
</head>
<body>

<nav>
    <a href="/" class="nav-logo">TDA<span>COURT</span></a>
    <a id="navBack" href="/" class="nav-back">← Back</a>
</nav>

<form method="POST" action="/book" id="bookingForm">
@csrf
<input type="hidden" name="court_id"       id="h_court_id">
<input type="hidden" name="court_name"     id="h_court_name">
<input type="hidden" name="time_slots"     id="h_time_slots">
<input type="hidden" name="payment_method" id="h_payment_method">

<div class="page-wrap">
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Book Your Court</div>
            <div class="card-sub">Fill out the form below to complete your reservation.</div>
        </div>
        <div class="wolfpax-logo">TDA<span>COURT</span></div>
    </div>

    <div class="steps" id="stepIndicator">
        <div class="step-item">
            <div class="step-bubble active" id="bubble1">1</div>
            <div class="step-label active" id="label1">Client Info</div>
        </div>
        <div class="step-connector" id="conn1"></div>
        <div class="step-item">
            <div class="step-bubble inactive" id="bubble2">2</div>
            <div class="step-label inactive" id="label2">Schedule</div>
        </div>
        <div class="step-connector" id="conn2"></div>
        <div class="step-item">
            <div class="step-bubble inactive" id="bubble3">3</div>
            <div class="step-label inactive" id="label3">Payment</div>
        </div>
    </div>

    <hr class="divider">

    <!-- STEP 1 -->
    <div class="step-panel active" id="panel1">
        <div class="section-heading">Step 1: Client Information</div>
        <div class="section-sub">Please provide your contact details to continue.</div>
        <div class="field">
            <label>Full Name <span class="req">*</span></label>
            <input type="text" id="fullName" name="full_name" placeholder="e.g. Juan dela Cruz" autocomplete="name" value="{{ old('full_name') }}">
            <div class="field-error" id="err-name">Please enter your full name.</div>
        </div>
        <div class="field">
            <label>Mobile Number <span class="req">*</span></label>
            <input type="tel" id="mobile" name="mobile_number" placeholder="09XXXXXXXXX" maxlength="11" inputmode="numeric" autocomplete="tel" value="{{ old('mobile_number') }}">
            <div class="field-error" id="err-mobile">Enter a valid PH mobile number (09XXXXXXXXX).</div>
        </div>
        <div class="field">
            <label>Email Address <span class="req">*</span></label>
            <input type="email" id="email" name="email" placeholder="you@email.com" autocomplete="email" inputmode="email" value="{{ old('email') }}">
            <div class="field-error" id="err-email">Please enter a valid email address.</div>
        </div>
        <div class="card-footer">
            <div></div>
            <button type="button" class="btn-next" onclick="goToStep2()">Next →</button>
        </div>
    </div>

    <!-- STEP 2 -->
    <div class="step-panel" id="panel2">
        <div class="section-heading">Step 2: Choose Your Schedule</div>
        <div class="section-sub">Select a court, pick a date, then choose <strong>one or more</strong> time slots.</div>

        <label style="font-size:13px;font-weight:600;display:block;margin-bottom:8px;">Select Court <span style="color:var(--danger)">*</span></label>
        <div class="court-options" id="courtOptions">
            <div class="court-option" onclick="selectCourt(this,'Court A – Hardcourt',300,0)">
                <input type="radio" name="court"><div class="court-dot"></div>
                <div class="court-emoji">🏸</div>
                <div class="court-info-text"><div class="court-info-name">Court A – Hardcourt</div><div class="court-info-desc">Indoor · Air-conditioned</div></div>
                <div class="court-price-tag">₱300/hr</div>
            </div>
            <div class="court-option" onclick="selectCourt(this,'Court B – Clay',250,1)">
                <input type="radio" name="court"><div class="court-dot"></div>
                <div class="court-emoji">🎾</div>
                <div class="court-info-text"><div class="court-info-name">Court B – Clay</div><div class="court-info-desc">Outdoor · Morning & evening</div></div>
                <div class="court-price-tag">₱250/hr</div>
            </div>
            <div class="court-option" onclick="selectCourt(this,'Court C – Synthetic',280,2)">
                <input type="radio" name="court"><div class="court-dot"></div>
                <div class="court-emoji">🏐</div>
                <div class="court-info-text"><div class="court-info-name">Court C – Synthetic</div><div class="court-info-desc">Covered · All-weather</div></div>
                <div class="court-price-tag">₱280/hr</div>
            </div>
        </div>
        <div class="field-error" id="err-court" style="margin-top:-12px;margin-bottom:14px;">Please select a court.</div>

        <div class="field date-field">
            <label>Date <span class="req">*</span></label>
            <input type="date" id="bookDate" name="booking_date" min="" value="{{ old('booking_date') }}" onchange="fetchBookedSlots()">
            <div class="field-error" id="err-date">Please select a date.</div>
        </div>

        <!-- SLOTS LOADING -->
        <div class="slots-loading" id="slotsLoading">⏳ Loading availability…</div>

        <!-- AM SLOTS -->
        <div id="slotSection" style="display:none">
            <div class="time-section-label"><span>Morning</span><span class="time-badge am">AM</span></div>
            <div class="slots-grid" id="amSlots"></div>

            <div class="time-section-label"><span>Afternoon & Evening</span><span class="time-badge pm">PM</span></div>
            <div class="slots-grid" id="pmSlots"></div>

            <!-- SELECTED COUNT -->
            <div class="selected-count" id="selectedCount">
                <span>✅</span>
                <span><span class="selected-count-num" id="selectedNum">0</span> slot(s) selected</span>
                <span style="color:var(--muted);font-weight:400">·</span>
                <span id="selectedTotal" style="color:var(--accent)">₱0 total</span>
            </div>

            <div class="slot-legend" style="margin-top:10px">
                <div class="legend-item"><div class="legend-dot" style="background:var(--surface);border:1.5px solid var(--line)"></div> Available</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--accent)"></div> Selected</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--danger-light);border:1.5px solid #fca5a5"></div> Already booked</div>
            </div>
        </div>

        <div class="field-error" id="err-slot" style="margin-top:8px;">Please select at least one time slot.</div>

        <div class="card-footer">
            <button type="button" class="btn-back" onclick="goToStep(1)">← Back</button>
            <button type="button" class="btn-next" onclick="goToStep3()">Next →</button>
        </div>
    </div>

    <!-- STEP 3 -->
    <div class="step-panel" id="panel3">
        <div class="section-heading">Step 3: Payment</div>
        <div class="section-sub">Review your booking and choose a payment method.</div>
        <div class="summary-box">
            <div class="summary-title">Booking Summary</div>
            <div class="summary-row"><span class="summary-key">Name</span><span class="summary-val" id="sum-name">—</span></div>
            <div class="summary-row"><span class="summary-key">Mobile</span><span class="summary-val" id="sum-mobile">—</span></div>
            <div class="summary-row"><span class="summary-key">Email</span><span class="summary-val" id="sum-email">—</span></div>
            <div class="summary-row"><span class="summary-key">Court</span><span class="summary-val" id="sum-court">—</span></div>
            <div class="summary-row"><span class="summary-key">Date</span><span class="summary-val" id="sum-date">—</span></div>
            <div class="summary-row" style="align-items:flex-start">
                <span class="summary-key">Time Slots</span>
                <div class="summary-slots" id="sum-slots"></div>
            </div>
            <div class="summary-total"><span>Total (<span id="sum-slot-count">0</span> hr)</span><span class="amount" id="sum-total">₱0</span></div>
        </div>
        <label style="font-size:13px;font-weight:600;display:block;margin-bottom:8px;">Payment Method <span style="color:var(--danger)">*</span></label>
        <div class="payment-options">
            <div class="pay-option" onclick="selectPay(this)"><input type="radio" name="payment"><div class="pay-dot"></div><div class="pay-emoji">💚</div><div class="pay-name">GCash</div></div>
            <div class="pay-option" onclick="selectPay(this)"><input type="radio" name="payment"><div class="pay-dot"></div><div class="pay-emoji">💜</div><div class="pay-name">PayMaya</div></div>
            <div class="pay-option" onclick="selectPay(this)"><input type="radio" name="payment"><div class="pay-dot"></div><div class="pay-emoji">🏦</div><div class="pay-name">Bank Transfer</div></div>
            <div class="pay-option" onclick="selectPay(this)"><input type="radio" name="payment"><div class="pay-dot"></div><div class="pay-emoji">🏢</div><div class="pay-name">Pay at the counter</div></div>
        </div>
        <div class="field-error" id="err-pay" style="margin-bottom:10px;">Please select a payment method.</div>
        <div class="card-footer">
            <button type="button" class="btn-back" onclick="goToStep(2)">← Back</button>
            <button type="button" class="btn-next" onclick="confirmBooking()">Confirm Booking ✓</button>
        </div>
    </div>

    <!-- SUCCESS -->
    <div class="step-panel" id="panelSuccess">
        <div style="text-align:center;padding:12px 0">
            <div style="font-size:52px;margin-bottom:14px">🎾</div>
            <div style="font-family:'Syne',sans-serif;font-size:24px;font-weight:800;margin-bottom:8px">Booking Confirmed!</div>
            <div style="font-size:14px;color:var(--muted);margin-bottom:22px">A confirmation will be noted for <strong id="conf-email"></strong>.</div>
            <div style="background:var(--accent-light);border:1.5px solid #c7d2fe;border-radius:14px;padding:18px;display:inline-block;text-align:left;min-width:260px;max-width:100%">
                <div style="font-size:11px;font-weight:700;color:var(--accent);margin-bottom:6px;text-transform:uppercase;letter-spacing:1px">Booking Reference</div>
                <div style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;letter-spacing:3px;color:var(--ink)" id="ref-code"></div>
                <div style="font-size:12px;color:var(--muted);margin-top:6px" id="ref-detail"></div>
            </div>
            <br>
            <a href="/" style="display:inline-block;margin-top:22px;padding:12px 28px;border-radius:10px;background:var(--accent);color:#fff;font-size:14px;font-weight:700;text-decoration:none">← Back to home</a>
        </div>
    </div>

</div>
</div>
</form>

<script>
const today = new Date().toISOString().split('T')[0];
document.getElementById('bookDate').min = today;

// All available slots
const AM_SLOTS = ['6:00–7:00 AM','7:00–8:00 AM','8:00–9:00 AM','9:00–10:00 AM','10:00–11:00 AM','11:00 AM–12:00 PM'];
const PM_SLOTS = ['12:00–1:00 PM','1:00–2:00 PM','2:00–3:00 PM','3:00–4:00 PM','4:00–5:00 PM','5:00–6:00 PM','6:00–7:00 PM','7:00–8:00 PM','8:00–9:00 PM','9:00–10:00 PM'];

let selectedCourt = null, selectedCourtId = null, selectedPay = null, courtPrice = 0;
let selectedSlots = [];   // ARRAY — multiple slots
let bookedSlots   = [];   // from DB

// ── STEP NAVIGATION ──
function goToStep(n) {
    for (let i = 1; i <= 3; i++) document.getElementById('panel'+i).classList.remove('active');
    document.getElementById('panel'+n).classList.add('active');
    updateIndicator(n);
    window.scrollTo({ top:0, behavior:'smooth' });
}

function updateIndicator(current) {
    [1,2,3].forEach(n => {
        const b = document.getElementById('bubble'+n);
        const l = document.getElementById('label'+n);
        b.className = 'step-bubble '+(n<current?'done':n===current?'active':'inactive');
        l.className = 'step-label '+(n<current?'done':n===current?'active':'inactive');
        b.textContent = n < current ? '✓' : n;
    });
    document.getElementById('conn1').className = 'step-connector'+(current>1?' done':'');
    document.getElementById('conn2').className = 'step-connector'+(current>2?' done':'');
}

function showErr(id) { document.getElementById(id).classList.add('show'); }
function hideErr(id) { document.getElementById(id).classList.remove('show'); }
function setFieldErr(inputId, errId, show) {
    const el = document.getElementById(inputId);
    if (el) el.classList.toggle('error', show);
    show ? showErr(errId) : hideErr(errId);
}

// ── STEP 1 → 2 ──
function goToStep2() {
    let ok = true;
    const name   = document.getElementById('fullName').value.trim();
    const mobile = document.getElementById('mobile').value.trim();
    const email  = document.getElementById('email').value.trim();
    setFieldErr('fullName','err-name', !name);                          if (!name) ok=false;
    setFieldErr('mobile','err-mobile', !/^09\d{9}$/.test(mobile));     if (!/^09\d{9}$/.test(mobile)) ok=false;
    setFieldErr('email','err-email', !/\S+@\S+\.\S+/.test(email));     if (!/\S+@\S+\.\S+/.test(email)) ok=false;
    if (ok) goToStep(2);
}

// ── STEP 2 → 3 ──
function goToStep3() {
    let ok = true;
    if (!selectedCourt) { showErr('err-court'); ok=false; } else hideErr('err-court');
    const date = document.getElementById('bookDate').value;
    setFieldErr('bookDate','err-date', !date); if (!date) ok=false;
    if (selectedSlots.length === 0) { showErr('err-slot'); ok=false; } else hideErr('err-slot');
    if (!ok) return;

    // Populate summary
    document.getElementById('sum-name').textContent   = document.getElementById('fullName').value.trim();
    document.getElementById('sum-mobile').textContent = document.getElementById('mobile').value.trim();
    document.getElementById('sum-email').textContent  = document.getElementById('email').value.trim();
    document.getElementById('sum-court').textContent  = selectedCourt;
    document.getElementById('sum-date').textContent   = formatDate(date);
    document.getElementById('sum-slot-count').textContent = selectedSlots.length;
    document.getElementById('sum-total').textContent  = '₱' + (selectedSlots.length * courtPrice).toLocaleString();

    // Slot tags in summary
    const slotsWrap = document.getElementById('sum-slots');
    slotsWrap.innerHTML = selectedSlots.map(s =>
        `<span class="summary-slot-tag">${s}</span>`
    ).join('');

    goToStep(3);
}

// ── CONFIRM ──
function confirmBooking() {
    if (!selectedPay) { showErr('err-pay'); return; }
    hideErr('err-pay');

    document.getElementById('h_court_id').value       = selectedCourtId;
    document.getElementById('h_court_name').value     = selectedCourt;
    document.getElementById('h_time_slots').value     = JSON.stringify(selectedSlots);
    document.getElementById('h_payment_method').value = selectedPay;

    document.getElementById('bookingForm').submit();
}

// ── COURT SELECTION ──
function selectCourt(el, name, price, id) {
    document.querySelectorAll('.court-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    selectedCourt = name; courtPrice = price; selectedCourtId = id;
    selectedSlots = [];
    hideErr('err-court');
    // Re-fetch slots if date already selected
    const date = document.getElementById('bookDate').value;
    if (date) fetchBookedSlots();
}

// ── SLOT SELECTION (MULTI) ──
function toggleSlot(el, slot) {
    if (el.classList.contains('booked') || el.classList.contains('loading')) return;

    if (el.classList.contains('selected')) {
        // Deselect
        el.classList.remove('selected');
        selectedSlots = selectedSlots.filter(s => s !== slot);
    } else {
        // Select
        el.classList.add('selected');
        selectedSlots.push(slot);
    }

    hideErr('err-slot');
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = selectedSlots.length;
    const total = count * courtPrice;
    const pill  = document.getElementById('selectedCount');

    if (count > 0) {
        pill.classList.add('show');
        document.getElementById('selectedNum').textContent   = count;
        document.getElementById('selectedTotal').textContent = '₱' + total.toLocaleString();
    } else {
        pill.classList.remove('show');
    }
}

// ── FETCH BOOKED SLOTS FROM DB ──
async function fetchBookedSlots() {
    const date    = document.getElementById('bookDate').value;
    const courtId = selectedCourtId;

    if (!date || courtId === null) return;

    // Show loading
    document.getElementById('slotsLoading').classList.add('show');
    document.getElementById('slotSection').style.display = 'none';
    selectedSlots = [];

    try {
        const res  = await fetch(`/api/slots?court_id=${courtId}&date=${date}`);
        const data = await res.json();
        bookedSlots = data.booked_slots || [];
    } catch(e) {
        bookedSlots = [];
    }

    document.getElementById('slotsLoading').classList.remove('show');
    document.getElementById('slotSection').style.display = 'block';
    renderSlots();
    updateSelectedCount();
}

function renderSlots() {
    renderSlotGroup('amSlots', AM_SLOTS);
    renderSlotGroup('pmSlots', PM_SLOTS);
}

function renderSlotGroup(containerId, slots) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    slots.forEach(slot => {
        const isBooked   = bookedSlots.includes(slot);
        const isSelected = selectedSlots.includes(slot);
        const div = document.createElement('div');
        div.className = 'slot' + (isBooked ? ' booked' : '') + (isSelected ? ' selected' : '');
        div.textContent = slot;
        if (isBooked) {
            div.title = '❌ Already booked';
        } else {
            div.onclick = () => toggleSlot(div, slot);
        }
        container.appendChild(div);
    });
}

// ── PAYMENT ──
function selectPay(el) {
    document.querySelectorAll('.pay-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    selectedPay = el.querySelector('.pay-name').textContent;
    hideErr('err-pay');
}

// ── UTILS ──
function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-PH', { weekday:'short', year:'numeric', month:'long', day:'numeric' });
}

['fullName','mobile','email'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', () => {
        el.classList.remove('error');
        hideErr({ fullName:'err-name', mobile:'err-mobile', email:'err-email' }[id]);
    });
});

// ── PRE-SELECT COURT FROM ?court= ──
const courtData = [
    { name:'Court A – Hardcourt', price:300 },
    { name:'Court B – Clay',      price:250 },
    { name:'Court C – Synthetic', price:280 },
];

function preSelectCourt() {
    const params  = new URLSearchParams(window.location.search);
    const courtId = parseInt(params.get('court'));
    const navBack = document.getElementById('navBack');

    if (!isNaN(courtId) && courtId >= 0 && courtId <= 2) {
        navBack.href        = `/courts/${courtId}`;
        navBack.textContent = '← Back to court';
    } else {
        navBack.href        = '/';
        navBack.textContent = '← Back to home';
    }

    if (isNaN(courtId) || courtId < 0 || courtId > 2) return;

    const options = document.querySelectorAll('.court-option');
    if (!options[courtId]) return;
    options.forEach(o => o.classList.remove('selected'));
    options[courtId].classList.add('selected');
    selectedCourt   = courtData[courtId].name;
    courtPrice      = courtData[courtId].price;
    selectedCourtId = courtId;

    const banner = document.createElement('div');
    banner.style.cssText = 'display:flex;align-items:center;gap:10px;background:#ecfdf5;border:1.5px solid #6ee7b7;border-radius:10px;padding:10px 14px;font-size:13px;color:#065f46;font-weight:500;margin-bottom:12px;';
    banner.innerHTML = `<span style="font-size:16px">✅</span><span><strong>${selectedCourt}</strong> pre-selected. You can change it below.</span><button type="button" onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;font-size:18px;color:#6b7280;line-height:1;">✕</button>`;
    const courtLabel = document.querySelector('#panel2 label');
    courtLabel.parentElement.insertBefore(banner, courtLabel);
}

// ══════════════════════════════════════════════════════
// SLOT LOCKING SYSTEM — drop this inside book.blade.php
// Replace the existing fetchBookedSlots, renderSlots,
// toggleSlot, and selectCourt functions with these.
// ══════════════════════════════════════════════════════

const AM_SLOTS = ['6:00–7:00 AM','7:00–8:00 AM','8:00–9:00 AM','9:00–10:00 AM','10:00–11:00 AM','11:00 AM–12:00 PM'];
const PM_SLOTS = ['12:00–1:00 PM','1:00–2:00 PM','2:00–3:00 PM','3:00–4:00 PM','4:00–5:00 PM','5:00–6:00 PM','6:00–7:00 PM','7:00–8:00 PM','8:00–9:00 PM','9:00–10:00 PM'];

let selectedCourt   = null, selectedCourtId = null, selectedPay = null;
let courtPrice      = 0,    selectedSlots   = [];
let bookedSlots     = [],   lockedSlots     = [];

// ── COUNTDOWN TIMERS per slot ──
// { slotName: { expiresAt: Date, timerId: intervalId, countdownEl: element } }
let slotTimers = {};

// ── FETCH AVAILABILITY (booked + locked by others) ──
async function fetchBookedSlots() {
    const date    = document.getElementById('bookDate').value;
    const courtId = selectedCourtId;
    if (!date || courtId === null) return;

    document.getElementById('slotsLoading').classList.add('show');
    document.getElementById('slotSection').style.display = 'none';
    clearAllTimers();
    selectedSlots = [];

    try {
        const res  = await fetch(`/api/slots?court_id=${courtId}&date=${date}`);
        const data = await res.json();
        bookedSlots = data.booked_slots || [];
        lockedSlots = data.locked_slots || [];
    } catch(e) {
        bookedSlots = []; lockedSlots = [];
    }

    document.getElementById('slotsLoading').classList.remove('show');
    document.getElementById('slotSection').style.display = 'block';
    renderSlots();
    updateSelectedCount();

    // Poll availability every 10 seconds to catch other users locking/unlocking
    if (window._slotPollTimer) clearInterval(window._slotPollTimer);
    window._slotPollTimer = setInterval(refreshAvailability, 10000);
}

async function refreshAvailability() {
    const date    = document.getElementById('bookDate').value;
    const courtId = selectedCourtId;
    if (!date || courtId === null) return;
    try {
        const res  = await fetch(`/api/slots?court_id=${courtId}&date=${date}`);
        const data = await res.json();
        bookedSlots = data.booked_slots || [];
        lockedSlots = data.locked_slots || [];
        // Re-render without clearing our own selected slots
        renderSlots();
    } catch(e) {}
}

// ── RENDER SLOT GRIDS ──
function renderSlots() {
    renderSlotGroup('amSlots', AM_SLOTS);
    renderSlotGroup('pmSlots', PM_SLOTS);
}

function renderSlotGroup(containerId, slots) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    slots.forEach(slot => {
        const isBooked   = bookedSlots.includes(slot);
        const isLocked   = lockedSlots.includes(slot);
        const isSelected = selectedSlots.includes(slot);

        const div = document.createElement('div');
        div.dataset.slot = slot;

        if (isBooked) {
            div.className   = 'slot booked';
            div.textContent = slot;
            div.title       = '❌ Already booked';
        } else if (isLocked) {
            div.className = 'slot locked';
            div.innerHTML = `<span style="display:block;font-size:10px">⏳ ${slot}</span><span class="lock-timer" style="display:block;font-size:9px;opacity:.8;margin-top:2px">Reserved</span>`;
            div.title     = '⏳ Someone else is reserving this slot';
        } else if (isSelected) {
            div.className = 'slot selected';
            div.onclick   = () => deselect(div, slot);

            // Show countdown if timer exists
            if (slotTimers[slot]) {
                const timerEl = document.createElement('span');
                timerEl.className = 'slot-countdown';
                timerEl.style.cssText = 'display:block;font-size:9px;margin-top:2px;opacity:.85;font-weight:700';
                div.innerHTML = `<span>${slot}</span>`;
                div.appendChild(timerEl);
                slotTimers[slot].countdownEl = timerEl;
            } else {
                div.textContent = slot;
            }
        } else {
            div.className   = 'slot available';
            div.textContent = slot;
            div.onclick     = () => selectSlot(div, slot);
        }

        container.appendChild(div);
    });
}

// ── SELECT SLOT → lock via API ──
async function selectSlot(el, slot) {
    if (el.classList.contains('booked') ||
        el.classList.contains('locked') ||
        el.classList.contains('loading')) return;

    const date    = document.getElementById('bookDate').value;
    const courtId = selectedCourtId;

    // Optimistically show loading
    el.classList.add('loading');
    el.textContent = '⏳';

    try {
        const res  = await fetch('/api/slots/lock', {
            method:  'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                'Accept':           'application/json',
            },
            body: JSON.stringify({ court_id: courtId, date, time_slot: slot }),
        });

        const data = await res.json();

        if (data.success) {
            // Lock acquired — add to selected
            selectedSlots.push(slot);
            hideErr('err-slot');
            updateSelectedCount();

            // Start countdown timer
            startSlotTimer(slot, data.expires_at);
            renderSlots(); // re-render to show countdown
        } else {
            // Lock failed — show why
            if (data.reason === 'locked') {
                showToast(`⏳ Someone is already reserving this slot. Try another!`, 'warning');
                // Mark as locked locally and re-render
                lockedSlots.push(slot);
            } else {
                showToast(`❌ ${data.message}`, 'error');
                bookedSlots.push(slot);
            }
            renderSlots();
        }
    } catch(e) {
        showToast('⚠️ Network error. Please try again.', 'error');
        el.classList.remove('loading');
        el.textContent = slot;
    }
}

// ── DESELECT SLOT → unlock via API ──
async function deselect(el, slot) {
    const date    = document.getElementById('bookDate').value;
    const courtId = selectedCourtId;

    selectedSlots = selectedSlots.filter(s => s !== slot);
    clearSlotTimer(slot);
    updateSelectedCount();
    renderSlots();

    // Release lock in background
    try {
        await fetch('/api/slots/unlock', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ court_id: courtId, date, time_slot: slot }),
        });
    } catch(e) {}
}

// ── COUNTDOWN TIMER ──
function startSlotTimer(slot, expiresAtIso) {
    const expiresAt = new Date(expiresAtIso);

    const timer = setInterval(() => {
        const secsLeft = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
        const mins     = Math.floor(secsLeft / 60);
        const secs     = secsLeft % 60;
        const label    = `${mins}:${String(secs).padStart(2,'0')}`;

        // Update countdown in slot UI
        if (slotTimers[slot]?.countdownEl) {
            slotTimers[slot].countdownEl.textContent = label;
            // Turn red in last 30 seconds
            slotTimers[slot].countdownEl.style.color = secsLeft <= 30 ? '#fca5a5' : '';
        }

        // Update global timer display
        updateTimerBanner();

        if (secsLeft <= 0) {
            // Lock expired — auto deselect
            clearSlotTimer(slot);
            selectedSlots = selectedSlots.filter(s => s !== slot);
            updateSelectedCount();
            renderSlots();
            showToast(`⏰ Your hold on "${slot}" expired. Please re-select it.`, 'warning');
        }
    }, 1000);

    slotTimers[slot] = { expiresAt, timerId: timer, countdownEl: null };
    updateTimerBanner();
}

function clearSlotTimer(slot) {
    if (slotTimers[slot]) {
        clearInterval(slotTimers[slot].timerId);
        delete slotTimers[slot];
    }
    updateTimerBanner();
}

function clearAllTimers() {
    Object.keys(slotTimers).forEach(slot => {
        clearInterval(slotTimers[slot].timerId);
    });
    slotTimers = {};
    updateTimerBanner();
}

// ── GLOBAL TIMER BANNER ──
function updateTimerBanner() {
    let banner = document.getElementById('slotTimerBanner');
    const keys = Object.keys(slotTimers);

    if (keys.length === 0) {
        if (banner) banner.style.display = 'none';
        return;
    }

    if (!banner) {
        banner = document.createElement('div');
        banner.id = 'slotTimerBanner';
        banner.style.cssText = `
            background:linear-gradient(135deg,#4f46e5,#7c3aed);
            color:#fff; border-radius:10px; padding:10px 14px;
            margin-top:12px; font-size:13px; font-weight:600;
            display:flex; align-items:center; gap:8px;
            animation:slideDown .3s ease;
        `;
        document.getElementById('selectedCount').after(banner);
    }

    // Find earliest expiry
    const earliest = Object.values(slotTimers)
        .map(t => t.expiresAt)
        .sort((a,b) => a - b)[0];
    const secsLeft = Math.max(0, Math.floor((earliest - Date.now()) / 1000));
    const mins     = Math.floor(secsLeft / 60);
    const secs     = secsLeft % 60;

    banner.style.display = 'flex';
    banner.innerHTML = `
        <span style="font-size:18px">⏱</span>
        <span>Your slot hold expires in
            <strong style="font-size:15px;${secsLeft <= 30 ? 'color:#fca5a5' : ''}">
                ${mins}:${String(secs).padStart(2,'0')}
            </strong>
            — complete your booking before time runs out!
        </span>`;
}

// ── TOAST NOTIFICATION ──
function showToast(message, type = 'info') {
    let toast = document.getElementById('slotToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'slotToast';
        toast.style.cssText = `
            position:fixed; bottom:24px; left:50%; transform:translateX(-50%);
            padding:12px 24px; border-radius:10px; font-size:14px; font-weight:600;
            z-index:9999; max-width:90vw; text-align:center;
            box-shadow:0 8px 32px rgba(0,0,0,.2);
            transition:opacity .3s, transform .3s;
            animation:slideUp .3s ease;
        `;
        document.body.appendChild(toast);
    }
    const colors = {
        warning: { bg:'#fffbeb', color:'#92400e', border:'#fcd34d' },
        error:   { bg:'#fef2f2', color:'#991b1b', border:'#fca5a5' },
        info:    { bg:'#eef2ff', color:'#3730a3', border:'#c7d2fe' },
    };
    const c = colors[type] || colors.info;
    toast.style.background   = c.bg;
    toast.style.color        = c.color;
    toast.style.border       = `1.5px solid ${c.border}`;
    toast.textContent        = message;
    toast.style.opacity      = '1';
    toast.style.display      = 'block';
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.style.display='none', 300); }, 4000);
}

// ── RELEASE ALL LOCKS ON PAGE LEAVE ──
function releaseAllLocks() {
    if (selectedSlots.length === 0) return;
    navigator.sendBeacon('/api/slots/unlock-all',
        new Blob([JSON.stringify({ _token: document.querySelector('meta[name="csrf-token"]').content })],
        { type:'application/json' })
    );
}
window.addEventListener('beforeunload', releaseAllLocks);
window.addEventListener('pagehide',     releaseAllLocks);

// ── UPDATE COURT SELECTION ──
function selectCourt(el, name, price, id) {
    // Release existing locks if changing court
    if (selectedSlots.length > 0) {
        releaseAllLocks();
        clearAllTimers();
    }
    document.querySelectorAll('.court-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    selectedCourt = name; courtPrice = price; selectedCourtId = id;
    selectedSlots = [];
    hideErr('err-court');
    const date = document.getElementById('bookDate').value;
    if (date) fetchBookedSlots();
}

function updateSelectedCount() {
    const c = selectedSlots.length, t = c * courtPrice;
    const pill = document.getElementById('selectedCount');
    if (c > 0) {
        pill.classList.add('show');
        document.getElementById('selectedNum').textContent   = c;
        document.getElementById('selectedTotal').textContent = '₱' + t.toLocaleString();
    } else {
        pill.classList.remove('show');
    }
}

// Stop polling when leaving step 2
function goToStep(n) {
    if (n !== 2 && window._slotPollTimer) {
        clearInterval(window._slotPollTimer);
    }
    for (let i=1;i<=3;i++) document.getElementById('panel'+i).classList.remove('active');
    document.getElementById('panel'+n).classList.add('active');
    updateIndicator(n);
    window.scrollTo({top:0,behavior:'smooth'});
}

preSelectCourt();
</script>
</body>
</html>