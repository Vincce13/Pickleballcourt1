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
    <title>Booking Confirmed – SZAMCOURT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        :root {
            --ink:#0f0f14;--muted:#6b6b80;--line:#e4e4ef;--surface:#f7f7fb;--white:#fff;
            --accent:#4f46e5;--accent-dark:#3730a3;--accent-light:#eef2ff;
            --green:#059669;--green-light:#ecfdf5;--amber:#d97706;--amber-light:#fffbeb;
            --red:#dc2626;--red-light:#fef2f2;
        }
        body { font-family:'Inter',sans-serif; background:var(--surface); color:var(--ink); min-height:100vh; }
        nav { background:var(--white); border-bottom:1px solid var(--line); padding:0 48px; height:64px; display:flex; align-items:center; justify-content:space-between; }
        .nav-logo { font-family:'Syne',sans-serif; font-weight:800; font-size:20px; letter-spacing:3px; color:var(--ink); text-decoration:none; }
        .nav-logo span { color:var(--accent); }
        .wrap { max-width:560px; margin:0 auto; padding:40px 20px 80px; }

        /* HEADER */
        .success-header { text-align:center; margin-bottom:24px; }
        .success-icon { font-size:56px; margin-bottom:10px; }
        .success-title { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; margin-bottom:6px; }
        .success-sub { font-size:14px; color:var(--muted); line-height:1.6; }

        /* BADGE */
        .badge-wrap { text-align:center; margin-bottom:18px; }
        .status-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 16px; border-radius:20px; font-size:12px; font-weight:700; background:var(--amber-light); color:var(--amber); }
        .status-badge.paid { background:var(--green-light); color:var(--green); }

        /* FLASH */
        .flash-success { background:var(--green-light); border:1.5px solid #6ee7b7; border-radius:10px; padding:12px 16px; font-size:13px; color:#065f46; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
        .flash-error   { background:var(--red-light);   border:1.5px solid #fca5a5; border-radius:10px; padding:12px 16px; font-size:13px; color:var(--red);   margin-bottom:16px; }

        /* REF BOX */
        .ref-box { background:var(--accent-light); border:1.5px solid #c7d2fe; border-radius:14px; padding:18px 20px; margin-bottom:18px; text-align:center; }
        .ref-label { font-size:11px; font-weight:700; color:var(--accent); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }
        .ref-code { font-family:'Syne',sans-serif; font-size:24px; font-weight:800; letter-spacing:4px; color:var(--ink); margin-bottom:4px; }
        .ref-sub { font-size:12px; color:var(--muted); }

        /* GCASH CARD */
        .gcash-card { background:linear-gradient(135deg,#007bff,#0056cc); border-radius:16px; padding:22px; margin-bottom:16px; color:#fff; }
        .gcash-header { text-align:center; margin-bottom:14px; }
        .gcash-logo { font-size:30px; margin-bottom:5px; }
        .gcash-title { font-family:'Syne',sans-serif; font-size:17px; font-weight:800; margin-bottom:3px; }
        .gcash-sub { font-size:12px; opacity:.85; }
        .gcash-steps { background:rgba(255,255,255,.12); border-radius:10px; padding:14px 16px; margin-bottom:12px; font-size:13px; line-height:1.9; }
        .gcash-number-box { background:#fff; border-radius:12px; padding:14px 18px; margin-bottom:12px; text-align:center; }
        .gcash-num-label { font-size:10px; font-weight:700; color:#007bff; text-transform:uppercase; letter-spacing:1px; margin-bottom:5px; }
        .gcash-num { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; color:var(--ink); letter-spacing:3px; }
        .gcash-account-name { font-size:12px; color:var(--muted); margin-top:3px; }
        .copy-btn { margin-top:8px; background:#007bff; color:#fff; border:none; border-radius:8px; padding:7px 16px; font-size:12px; font-weight:600; cursor:pointer; font-family:'Inter',sans-serif; transition:background .15s; }
        .copy-btn.copied { background:var(--green); }
        .amount-row { background:rgba(255,255,255,.15); border-radius:10px; padding:12px 16px; display:flex; justify-content:space-between; align-items:center; }
        .amount-label { font-size:13px; font-weight:600; }
        .amount-value { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; }

        /* RECEIPT UPLOAD */
        .receipt-section { background:var(--white); border:1px solid var(--line); border-radius:14px; overflow:hidden; margin-bottom:16px; }
        .receipt-head { padding:14px 18px; border-bottom:1px solid var(--line); background:var(--surface); display:flex; align-items:center; justify-content:space-between; }
        .receipt-head-title { font-size:13px; font-weight:700; color:var(--ink); }
        .receipt-uploaded-badge { background:var(--green-light); color:var(--green); font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; }
        .receipt-body { padding:18px; }

        /* UPLOADED RECEIPT PREVIEW */
        .receipt-preview { text-align:center; margin-bottom:14px; }
        .receipt-img { max-width:100%; max-height:280px; border-radius:10px; border:1px solid var(--line); object-fit:contain; cursor:pointer; transition:opacity .15s; }
        .receipt-img:hover { opacity:.9; }
        .receipt-uploaded-time { font-size:11px; color:var(--muted); margin-top:6px; }

        /* UPLOAD DROPZONE */
        .dropzone {
            border:2px dashed var(--line); border-radius:12px;
            padding:28px 16px; text-align:center; cursor:pointer;
            transition:all .2s; background:var(--surface);
            position:relative;
        }
        .dropzone:hover, .dropzone.dragover { border-color:var(--accent); background:var(--accent-light); }
        .dropzone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .dropzone-icon { font-size:32px; margin-bottom:8px; }
        .dropzone-text { font-size:14px; font-weight:600; color:var(--ink); margin-bottom:4px; }
        .dropzone-sub { font-size:12px; color:var(--muted); }
        .dropzone-preview { display:none; margin-top:12px; }
        .dropzone-preview img { max-height:160px; border-radius:8px; border:1px solid var(--line); }

        .upload-btn { display:block; width:100%; padding:12px; border-radius:10px; background:var(--accent); color:#fff; font-size:14px; font-weight:700; border:none; cursor:pointer; font-family:'Inter',sans-serif; margin-top:12px; transition:background .15s; }
        .upload-btn:hover { background:var(--accent-dark); }
        .upload-btn:disabled { background:#a5b4fc; cursor:not-allowed; }
        .upload-note { font-size:11px; color:var(--muted); margin-top:8px; text-align:center; }

        /* NOTICES */
        .pending-notice { background:var(--amber-light); border:1.5px solid #fcd34d; border-radius:12px; padding:13px 16px; font-size:13px; color:#92400e; margin-bottom:16px; line-height:1.6; }
        .paid-notice { background:var(--green-light); border:1.5px solid #6ee7b7; border-radius:12px; padding:13px 16px; font-size:13px; color:#065f46; margin-bottom:16px; line-height:1.6; }

        /* SUMMARY */
        .summary-box { background:var(--white); border:1px solid var(--line); border-radius:14px; padding:18px; margin-bottom:18px; }
        .summary-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; color:var(--muted); margin-bottom:12px; }
        .summary-row { display:flex; justify-content:space-between; font-size:13px; padding:8px 0; border-bottom:1px solid var(--line); gap:12px; }
        .summary-row:last-child { border-bottom:none; }
        .summary-key { color:var(--muted); flex-shrink:0; }
        .summary-val { font-weight:600; text-align:right; word-break:break-word; }
        .slot-tags { display:flex; flex-wrap:wrap; gap:4px; justify-content:flex-end; }
        .slot-tag { background:var(--accent-light); color:var(--accent); font-size:11px; font-weight:600; padding:2px 8px; border-radius:6px; }
        .total-row { display:flex; justify-content:space-between; font-size:15px; font-weight:700; padding-top:12px; margin-top:4px; border-top:2px solid var(--line); }
        .total-amount { color:var(--accent); font-size:18px; }

        /* ACTIONS */
        .actions { display:flex; gap:10px; flex-wrap:wrap; }
        .btn-primary { flex:1; padding:12px 20px; border-radius:10px; background:var(--accent); color:#fff; font-size:14px; font-weight:700; text-decoration:none; text-align:center; transition:background .15s; min-width:130px; }
        .btn-primary:hover { background:var(--accent-dark); }
        .btn-outline { flex:1; padding:12px 20px; border-radius:10px; border:1.5px solid var(--line); color:var(--muted); font-size:14px; font-weight:600; text-decoration:none; text-align:center; transition:all .15s; min-width:130px; }
        .btn-outline:hover { border-color:var(--ink); color:var(--ink); }

        /* LIGHTBOX */
        .lightbox { display:none; position:fixed; inset:0; background:rgba(0,0,0,.88); z-index:999; align-items:center; justify-content:center; padding:20px; }
        .lightbox.open { display:flex; }
        .lightbox img { max-width:90vw; max-height:90vh; border-radius:12px; object-fit:contain; }
        .lightbox-close { position:absolute; top:16px; right:20px; background:none; border:none; color:#fff; font-size:28px; cursor:pointer; }

        @media (max-width:500px) {
            nav { padding:0 16px; }
            .wrap { padding:24px 14px 60px; }
            .gcash-num { font-size:20px; }
            .actions { flex-direction:column; }
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
    </style>
</head>
<body>

<nav>
    <a href="/" class="nav-logo">SZAM<span>COURT</span></a>
</nav>

<div class="wrap">

    <!-- FLASH MESSAGES -->
    @if(session('receipt_success'))
    <div class="flash-success">✅ {{ session('receipt_success') }}</div>
    @endif
    @if($errors->has('receipt'))
    <div class="flash-error">⚠️ {{ $errors->first('receipt') }}</div>
    @endif

    <!-- HEADER -->
    <div class="success-header">
        <div class="success-icon">🎾</div>
        <div class="success-title">Booking Confirmed!</div>
        <div class="success-sub">A confirmation has been noted for <strong>{{ $reservation->email }}</strong>.</div>
    </div>

    <!-- STATUS BADGE -->
    <div class="badge-wrap" id="statusBadgeWrap">
        @if($reservation->status === 'confirmed')
            <span class="status-badge paid">✅ Booking Confirmed</span>
        @elseif($reservation->receipt_path)
            <span class="status-badge" style="background:#eef2ff;color:#4f46e5;">📎 Receipt Uploaded — Awaiting Admin Confirmation</span>
        @else
            <span class="status-badge">⏳ Pending Payment</span>
        @endif
    </div>

    <!-- REFERENCE -->
    <div class="ref-box">
        <div class="ref-label">Booking Reference</div>
        <div class="ref-code">{{ $reservation->reference_number }}</div>
        <div class="ref-sub">Show this reference at the counter on your booking day.</div>
    </div>

    <!-- GCASH INSTRUCTIONS (only if GCash + not yet paid) -->
    @if($reservation->payment_method === 'GCash' && $reservation->payment_status !== 'paid')
    <div class="gcash-card">
        <div class="gcash-header">
            <div class="gcash-logo">💚</div>
            <div class="gcash-title">Pay via GCash</div>
            <div class="gcash-sub">Send the exact amount to the number below</div>
        </div>
        <div class="gcash-steps">
            1️⃣ Open your <strong>GCash app</strong><br>
            2️⃣ Tap <strong>Send Money</strong> → enter the number below<br>
            3️⃣ Enter exact amount: <strong>₱{{ number_format($reservation->amount) }}</strong><br>
            4️⃣ In the message/note, type: <strong>{{ $reservation->reference_number }}</strong><br>
            5️⃣ Send and upload your <strong>GCash receipt screenshot</strong> below
        </div>
        <div class="gcash-number-box">
            <div class="gcash-num-label">GCash Number</div>
            <div class="gcash-num" id="gcashNum">0966 615 4780</div>
            {{-- ↑ CHANGE TO YOUR GCASH NUMBER --}}
            <div class="gcash-account-name">SZAMCOURT Reservation</div>
            <button class="copy-btn" id="copyBtn" onclick="copyNumber()">📋 Copy Number</button>
        </div>
        <div class="amount-row">
            <span class="amount-label">⚠️ Exact amount:</span>
            <span class="amount-value">₱{{ number_format($reservation->amount) }}</span>
        </div>
    </div>
    @elseif($reservation->payment_status === 'paid')
    <div class="paid-notice">✅ <strong>GCash payment verified!</strong> Your booking is confirmed. A confirmation email has been sent to <strong>{{ $reservation->email }}</strong>.</div>
    @else
    <div class="pending-notice">⏳ Payment method: <strong>{{ $reservation->payment_method }}</strong>. Please settle payment upon arrival at the counter.</div>
    @endif

    <!-- RECEIPT UPLOAD SECTION -->
    @if($reservation->payment_method === 'GCash')
    <div class="receipt-section">
        <div class="receipt-head">
            <div class="receipt-head-title">📎 GCash Receipt Screenshot</div>
            @if($reservation->receipt_path)
                <span class="receipt-uploaded-badge">✅ Uploaded</span>
            @endif
        </div>
        <div class="receipt-body">

            {{-- ALREADY UPLOADED — show preview --}}
            @if($reservation->receipt_path)
            <div class="receipt-preview">
                <img src="{{ asset('storage/' . $reservation->receipt_path) }}"
                     class="receipt-img"
                     alt="GCash Receipt"
                     onclick="openLightbox(this.src)">
                <div class="receipt-uploaded-time">
                    Uploaded {{ $reservation->receipt_uploaded_at?->diffForHumans() }}
                </div>
            </div>
            <p style="font-size:13px;color:var(--muted);margin-bottom:12px;text-align:center">
                Want to replace it? Upload a new one below.
            </p>
            @endif

            {{-- UPLOAD FORM --}}
            @if($reservation->payment_status !== 'paid')
            <form method="POST"
                  action="{{ route('receipt.upload', $reservation->reference_number) }}"
                  enctype="multipart/form-data"
                  id="receiptForm">
                @csrf

                <div class="dropzone" id="dropzone">
                    <input type="file" name="receipt" id="receiptInput"
                           accept="image/jpeg,image/png,image/webp"
                           onchange="previewFile(this)">
                    <div id="dropzoneContent">
                        <div class="dropzone-icon">📸</div>
                        <div class="dropzone-text">
                            {{ $reservation->receipt_path ? 'Upload new receipt' : 'Upload GCash receipt' }}
                        </div>
                        <div class="dropzone-sub">JPG, PNG or WEBP · Max 5MB · Tap to select</div>
                    </div>
                    <div class="dropzone-preview" id="dropzonePreview">
                        <img id="previewImg" src="" alt="Preview">
                        <div style="font-size:12px;color:var(--accent);margin-top:6px;font-weight:600" id="previewName"></div>
                    </div>
                </div>

                <button type="submit" class="upload-btn" id="uploadBtn" disabled>
                    📤 Upload Receipt
                </button>
                <div class="upload-note">Your receipt will be reviewed by the admin to confirm your payment.</div>
            </form>
            @else
            <p style="text-align:center;font-size:13px;color:var(--green);font-weight:600;padding:8px 0">
                ✅ Payment verified — no further action needed.
            </p>
            @endif

        </div>
    </div>
    @endif

    <!-- PENDING NOTICE (after upload) -->
    @if($reservation->payment_method === 'GCash' && $reservation->payment_status !== 'paid' && $reservation->receipt_path)
    <div class="pending-notice">
        ⏳ Receipt uploaded! The admin will verify your GCash payment and confirm your booking. You'll receive an email at <strong>{{ $reservation->email }}</strong> once confirmed.
    </div>
    @elseif($reservation->payment_method === 'GCash' && $reservation->payment_status !== 'paid' && !$reservation->receipt_path)
    <div class="pending-notice">
        ⏳ Your booking is <strong>pending payment</strong>. Please upload your GCash receipt above so we can confirm your booking quickly.
    </div>
    @endif

    <!-- BOOKING SUMMARY -->
    <div class="summary-box">
        <div class="summary-title">Booking Summary</div>
        <div class="summary-row"><span class="summary-key">Name</span><span class="summary-val">{{ $reservation->full_name }}</span></div>
        <div class="summary-row"><span class="summary-key">Mobile</span><span class="summary-val">{{ $reservation->mobile_number }}</span></div>
        <div class="summary-row"><span class="summary-key">Email</span><span class="summary-val">{{ $reservation->email }}</span></div>
        <div class="summary-row"><span class="summary-key">Court</span><span class="summary-val">{{ $reservation->court_name }}</span></div>
        <div class="summary-row"><span class="summary-key">Date</span><span class="summary-val">{{ $reservation->formatted_date }}</span></div>
        <div class="summary-row" style="align-items:flex-start">
            <span class="summary-key">Time Slots</span>
            <div class="slot-tags">
                @if(is_array($reservation->time_slots))
                    @foreach($reservation->time_slots as $slot)
                        <span class="slot-tag">{{ $slot }}</span>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="summary-row"><span class="summary-key">Payment</span><span class="summary-val">{{ $reservation->payment_method }}</span></div>
        <div class="total-row"><span>Total</span><span class="total-amount">₱{{ number_format($reservation->amount) }}</span></div>
    </div>

    <!-- ACTIONS -->
    <div class="actions">
        <a href="/" class="btn-primary">← Back to home</a>
        <a href="{{ route('reservations.show', $reservation->reference_number) }}" class="btn-outline">View booking status</a>
    </div>

</div>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">✕</button>
    <img id="lightboxImg" src="" alt="Receipt">
</div>

<script>
// Copy GCash number
function copyNumber() {
    navigator.clipboard.writeText('09666154780'); // ← CHANGE TO YOUR NUMBER
    const btn = document.getElementById('copyBtn');
    btn.textContent = '✅ Copied!';
    btn.classList.add('copied');
    setTimeout(() => { btn.textContent = '📋 Copy Number'; btn.classList.remove('copied'); }, 2000);
}

// File preview
function previewFile(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('dropzoneContent').style.display = 'none';
        const preview = document.getElementById('dropzonePreview');
        preview.style.display = 'block';
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewName').textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        document.getElementById('uploadBtn').disabled = false;
        document.getElementById('uploadBtn').textContent = '📤 Upload This Receipt';
    };
    reader.readAsDataURL(file);
}

// Drag and drop
const dropzone = document.getElementById('dropzone');
if (dropzone) {
    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('dragover'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) {
            document.getElementById('receiptInput').files = e.dataTransfer.files;
            previewFile(document.getElementById('receiptInput'));
        }
    });
}

// Lightbox
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
</script>
<script>
// ── AUTO REFRESH POLLING ──
// Poll every 8 seconds to check if admin has confirmed the booking
@if($reservation->status !== 'confirmed')
(function() {
    const reference  = '{{ $reservation->reference_number }}';
    const pollUrl    = '/booking/status/' + reference;
    let pollInterval = null;
    let dotCount     = 0;

    // Show a subtle polling indicator
    const indicator = document.createElement('div');
    indicator.id    = 'pollIndicator';
    indicator.style.cssText = 'text-align:center;font-size:12px;color:var(--muted);margin-top:10px;padding:8px;';
    indicator.innerHTML     = '🔄 Checking for updates...';

    const wrap = document.querySelector('.badge-wrap');
    if (wrap) wrap.after(indicator);

    async function checkStatus() {
        try {
            const res  = await fetch(pollUrl);
            const data = await res.json();

            if (data.status === 'confirmed') {
                clearInterval(pollInterval);
                showConfirmed(data);
            } else {
                dotCount = (dotCount + 1) % 4;
                indicator.innerHTML = '🔄 Checking for confirmation' + '.'.repeat(dotCount + 1);
            }
        } catch (e) {
            // Silently ignore network errors
        }
    }

    function showConfirmed(data) {
        // Confetti effect
        launchConfetti();

        // Update icon
        const icon = document.getElementById('pageIcon');
        if (icon) { icon.textContent = '🎉'; icon.style.animation = 'pop .4s ease'; }

        // Update title
        const title = document.getElementById('pageTitle');
        if (title) { title.textContent = 'Booking Confirmed! 🎉'; }

        // Update sub text
        const sub = document.querySelector('.success-sub');
        if (sub) sub.innerHTML = 'Your booking has been <strong>confirmed</strong> by the admin. See you on the court!';

        // Update badge
        const badgeWrap = document.getElementById('statusBadgeWrap');
        if (badgeWrap) {
            badgeWrap.innerHTML = '<span class="status-badge paid" style="font-size:14px;padding:8px 20px;">✅ Booking Confirmed!</span>';
        }

        // Hide GCash card and upload section (payment done)
        const gcashCard = document.querySelector('.gcash-card');
        if (gcashCard) gcashCard.style.display = 'none';

        const receiptSection = document.querySelector('.receipt-section');
        if (receiptSection) receiptSection.style.display = 'none';

        // Hide pending notice
        document.querySelectorAll('.pending-notice').forEach(el => el.style.display = 'none');

        // Remove poll indicator
        const ind = document.getElementById('pollIndicator');
        if (ind) ind.remove();

        // Show big confirmed banner
        const banner = document.createElement('div');
        banner.style.cssText = `
            background:linear-gradient(135deg,#059669,#10b981);
            border-radius:16px; padding:24px; margin-bottom:16px;
            text-align:center; color:#fff;
            animation: slideDown .5s cubic-bezier(.22,1,.36,1);
        `;
        banner.innerHTML = `
            <div style="font-size:36px;margin-bottom:8px;">🎾✅</div>
            <div style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;margin-bottom:6px;">You're all set!</div>
            <div style="font-size:13px;opacity:.9;margin-bottom:14px;">
                A confirmation email has been sent to <strong>{{ $reservation->email }}</strong>.
            </div>
            <div style="background:rgba(255,255,255,.2);border-radius:10px;padding:10px 16px;display:inline-block;">
                <div style="font-size:11px;opacity:.8;letter-spacing:1px;text-transform:uppercase;margin-bottom:4px;">Reference</div>
                <div style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;letter-spacing:3px;">{{ $reservation->reference_number }}</div>
            </div>
        `;

        const refBox = document.querySelector('.ref-box');
        if (refBox) refBox.after(banner);

        // Scroll to top smoothly
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ── CONFETTI ──
    function launchConfetti() {
        const colors = ['#4f46e5','#059669','#f59e0b','#ec4899','#06b6d4'];
        for (let i = 0; i < 80; i++) {
            setTimeout(() => {
                const el = document.createElement('div');
                el.style.cssText = `
                    position:fixed;
                    top:-10px;
                    left:${Math.random() * 100}vw;
                    width:${6 + Math.random() * 8}px;
                    height:${6 + Math.random() * 8}px;
                    background:${colors[Math.floor(Math.random() * colors.length)]};
                    border-radius:${Math.random() > .5 ? '50%' : '2px'};
                    opacity:1;
                    z-index:9999;
                    pointer-events:none;
                    animation: confettiFall ${1.5 + Math.random() * 2}s ease forwards;
                `;
                document.body.appendChild(el);
                setTimeout(() => el.remove(), 3500);
            }, i * 30);
        }
    }

    // Start polling every 8 seconds
    pollInterval = setInterval(checkStatus, 8000);

    // Also check immediately after 2s
    setTimeout(checkStatus, 2000);
})();
@endif

// ── COPY NUMBER ──
function copyNumber() {
    navigator.clipboard.writeText('09666154780');
    const btn = document.getElementById('copyBtn');
    btn.textContent = '✅ Copied!';
    btn.classList.add('copied');
    setTimeout(() => { btn.textContent = '📋 Copy Number'; btn.classList.remove('copied'); }, 2000);
}
</script>

<style>
@keyframes pop { 0%{transform:scale(1)} 50%{transform:scale(1.3)} 100%{transform:scale(1)} }
@keyframes slideDown { from{opacity:0;transform:translateY(-20px)} to{opacity:1;transform:translateY(0)} }
@keyframes confettiFall {
    0%   { transform:translateY(0) rotate(0deg); opacity:1; }
    100% { transform:translateY(100vh) rotate(720deg); opacity:0; }
}
</style>
</body>
</html>