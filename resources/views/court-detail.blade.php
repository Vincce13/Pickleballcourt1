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
    <title>Court Details – TDA COURT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink: #0f0f14; --ink2: #2a2a38; --muted: #6b6b80;
            --line: #e4e4ef; --surface: #f7f7fb; --white: #ffffff;
            --accent: #4f46e5; --accent-dark: #3730a3; --accent-light: #eef2ff;
            --green: #059669; --green-light: #ecfdf5;
            --nav-h: 64px;
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: var(--surface); color: var(--ink); min-height: 100vh; overflow-x: hidden; }

        /* ── NAV ── */
        nav {
            background: rgba(255,255,255,0.96); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--line);
            padding: 0 48px; height: var(--nav-h);
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 200;
        }
        .nav-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 20px; letter-spacing: 3px; color: var(--ink); text-decoration: none; }
        .nav-logo span { color: var(--accent); }
        .nav-back { font-size: 14px; color: var(--muted); text-decoration: none; display: flex; align-items: center; gap: 6px; font-weight: 500; transition: color .15s; }
        .nav-back:hover { color: var(--ink); }

        /* ── BREADCRUMB ── */
        .breadcrumb { max-width: 1100px; margin: 0 auto; padding: 14px 48px 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); flex-wrap: wrap; }
        .breadcrumb a { color: var(--muted); text-decoration: none; }
        .breadcrumb a:hover { color: var(--accent); }
        .breadcrumb span { color: var(--ink); font-weight: 500; }

        /* ── TABS ── */
        .tabs-wrap { max-width: 1100px; margin: 0 auto; padding: 14px 48px 0; }
        .court-tabs { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
        .court-tabs::-webkit-scrollbar { display: none; }
        .court-tab { padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; border: 1.5px solid var(--line); background: var(--white); color: var(--muted); cursor: pointer; white-space: nowrap; transition: all .15s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; flex-shrink: 0; -webkit-tap-highlight-color: transparent; }
        .court-tab:hover { border-color: var(--accent); color: var(--accent); }
        .court-tab.active { background: var(--accent); color: #fff; border-color: var(--accent); }

        /* ── PAGE LAYOUT ── */
        .page-wrap { max-width: 1100px; margin: 0 auto; padding: 20px 48px 100px; display: grid; grid-template-columns: 1fr 340px; gap: 32px; align-items: start; }

        /* ── GALLERY ── */
        .gallery { margin-bottom: 24px; }
        .gallery-main { width: 100%; height: 380px; border-radius: 16px; overflow: hidden; position: relative; cursor: pointer; background: #e2e8f0; touch-action: pan-y; }
        .img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 80px; user-select: none; }
        .photo-count { position: absolute; bottom: 12px; right: 12px; background: rgba(0,0,0,.55); color: #fff; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; backdrop-filter: blur(6px); }
        .gallery-thumbs { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 8px; }
        .thumb { height: 72px; border-radius: 10px; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: border-color .15s; background: #e2e8f0; -webkit-tap-highlight-color: transparent; }
        .thumb:hover, .thumb.active { border-color: var(--accent); }
        .thumb-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 24px; }

        /* ── COURT INFO ── */
        .court-info-block { margin-bottom: 24px; }
        .court-eyebrow { display: inline-flex; align-items: center; gap: 6px; background: var(--green-light); color: var(--green); font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .court-eyebrow::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--green); }
        .court-name { font-family: 'Syne', sans-serif; font-size: clamp(24px, 4vw, 32px); font-weight: 800; color: var(--ink); line-height: 1.15; margin-bottom: 8px; }
        .court-tagline { font-size: 15px; color: var(--muted); line-height: 1.6; }

        /* ── SECTION LABELS ── */
        .section-label { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--muted); margin-bottom: 10px; margin-top: 24px; }

        /* ── FEATURES ── */
        .features-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 4px; }
        .feature-item { display: flex; align-items: center; gap: 10px; padding: 11px 13px; border-radius: 12px; background: var(--white); border: 1px solid var(--line); font-size: 13px; font-weight: 500; color: var(--ink); }
        .feature-icon { font-size: 18px; flex-shrink: 0; }

        /* ── DESCRIPTION ── */
        .description-text { font-size: 14px; color: var(--muted); line-height: 1.8; margin-bottom: 4px; }

        /* ── RULES ── */
        .rules-list { list-style: none; margin-bottom: 4px; }
        .rules-list li { display: flex; align-items: flex-start; gap: 10px; font-size: 14px; color: var(--muted); padding: 9px 0; border-bottom: 1px solid var(--line); line-height: 1.5; }
        .rules-list li:last-child { border-bottom: none; }
        .rules-list li::before { content: '•'; color: var(--accent); font-size: 18px; line-height: 1.2; flex-shrink: 0; }

        /* ── SCHEDULE PREVIEW ── */
        .schedule-preview { background: var(--white); border-radius: 14px; border: 1px solid var(--line); padding: 18px; margin-bottom: 4px; }
        .schedule-day { font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 10px; }
        .mini-slots { display: flex; flex-wrap: wrap; gap: 6px; }
        .mini-slot { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; border: 1px solid var(--line); background: var(--surface); color: var(--ink); white-space: nowrap; }
        .mini-slot.taken { background: #f3f4f6; color: #d1d5db; text-decoration: line-through; border-color: #f3f4f6; }

        /* ── BOOKING CARD (desktop) ── */
        .booking-card { background: var(--white); border-radius: 20px; border: 1px solid var(--line); padding: 26px 22px; box-shadow: 0 4px 32px rgba(0,0,0,.07); position: sticky; top: calc(var(--nav-h) + 16px); }
        .booking-price { font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 800; color: var(--ink); margin-bottom: 4px; }
        .booking-price span { font-size: 15px; font-weight: 400; color: var(--muted); }
        .booking-divider { border: none; border-top: 1px solid var(--line); margin: 16px 0; }
        .booking-info-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding: 8px 0; border-bottom: 1px solid var(--line); gap: 8px; }
        .booking-info-row:last-of-type { border-bottom: none; }
        .booking-info-key { color: var(--muted); }
        .booking-info-val { font-weight: 600; color: var(--ink); text-align: right; }
        .btn-book-now { display: block; width: 100%; padding: 14px; border-radius: 12px; background: var(--accent); color: #fff; font-size: 15px; font-weight: 700; text-align: center; text-decoration: none; border: none; cursor: pointer; box-shadow: 0 4px 16px rgba(79,70,229,.3); transition: background .15s; margin-top: 18px; font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .btn-book-now:hover { background: var(--accent-dark); }
        .availability-status { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--green); margin-top: 12px; justify-content: center; }
        .availability-status::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: var(--green); flex-shrink: 0; }
        .share-row { display: flex; gap: 10px; margin-top: 14px; }
        .share-btn { flex: 1; padding: 9px; border-radius: 10px; border: 1.5px solid var(--line); background: transparent; font-size: 13px; font-weight: 500; color: var(--muted); cursor: pointer; transition: all .15s; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; gap: 5px; -webkit-tap-highlight-color: transparent; }
        .share-btn:hover { border-color: var(--ink); color: var(--ink); }

        /* ── MOBILE STICKY BOOKING BAR ── */
        .mobile-book-bar {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 150;
            background: var(--white); border-top: 1px solid var(--line);
            padding: 12px 20px 20px;
            box-shadow: 0 -4px 24px rgba(0,0,0,.08);
        }
        .mobile-book-bar-inner { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .mobile-book-price { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; color: var(--ink); line-height: 1; }
        .mobile-book-price small { font-size: 12px; font-weight: 400; color: var(--muted); display: block; margin-top: 2px; }
        .mobile-book-btn { flex: 1; padding: 13px; border-radius: 12px; background: var(--accent); color: #fff; font-size: 15px; font-weight: 700; text-align: center; text-decoration: none; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(79,70,229,.3); font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .mobile-avail { font-size: 11px; font-weight: 600; color: var(--green); text-align: right; }

        /* ── LIGHTBOX ── */
        .lightbox { display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0,0,0,.9); backdrop-filter: blur(6px); align-items: center; justify-content: center; }
        .lightbox.open { display: flex; }
        .lightbox-inner { position: relative; max-width: 90vw; width: 100%; }
        .lightbox-close { position: absolute; top: -48px; right: 0; background: transparent; border: none; color: #fff; font-size: 30px; cursor: pointer; padding: 8px; line-height: 1; }
        .lightbox-placeholder { width: 100%; height: clamp(260px, 50vw, 500px); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: clamp(60px, 15vw, 130px); background: #1e1e2e; }
        .lb-nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,.18); border: none; color: #fff; font-size: 22px; padding: 10px 14px; border-radius: 50%; cursor: pointer; transition: background .15s; -webkit-tap-highlight-color: transparent; }
        .lb-nav:hover { background: rgba(255,255,255,.32); }
        .lb-prev { left: -50px; }
        .lb-next { right: -50px; }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            nav { padding: 0 20px; }
            .breadcrumb { padding: 12px 20px 0; }
            .tabs-wrap { padding: 10px 20px 0; }
            .page-wrap { grid-template-columns: 1fr; padding: 16px 20px 120px; gap: 0; }
            .right-col { display: none; } /* replaced by sticky bar */
            .mobile-book-bar { display: block; }
            .gallery-main { height: 240px; border-radius: 14px; }
            .gallery-thumbs { grid-template-columns: repeat(4, 1fr); gap: 6px; }
            .thumb { height: 62px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            .lb-prev { left: -8px; }
            .lb-next { right: -8px; }
        }

        @media (max-width: 500px) {
            .gallery-main { height: 200px; }
            .gallery-thumbs { grid-template-columns: repeat(4, 1fr); gap: 5px; }
            .thumb { height: 52px; }
            .thumb-placeholder { font-size: 18px; }
            .img-placeholder { font-size: 60px; }
            .features-grid { grid-template-columns: 1fr 1fr; gap: 7px; }
            .feature-item { padding: 10px; font-size: 12px; }
            .court-tab { font-size: 12px; padding: 7px 12px; }
            .mini-slot { font-size: 10px; padding: 4px 8px; }
            .mobile-book-price { font-size: 20px; }
        }

        @media (max-width: 360px) {
            .features-grid { grid-template-columns: 1fr; }
            .gallery-thumbs { grid-template-columns: repeat(4, 1fr); }
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

<!-- NAV -->
<nav>
    <a href="/" class="nav-logo">TDA<span>COURT</span></a>
    <a href="/" class="nav-back" id="navBack">← Back to home</a>
</nav>

<!-- BREADCRUMB -->
<div class="breadcrumb">
    <a href="/">Home</a> › <a href="/#courts">Courts</a> › <span id="bc-name">Court A – Hardcourt</span>
</div>

<!-- TABS -->
<div class="tabs-wrap">
    <div class="court-tabs">
        <a class="court-tab active" onclick="loadCourt(0);return false;" href="#">🏸 Court A</a>
        <a class="court-tab"       onclick="loadCourt(1);return false;" href="#">🎾 Court B</a>
        <a class="court-tab"       onclick="loadCourt(2);return false;" href="#">🏐 Court C</a>
    </div>
</div>

<!-- PAGE BODY -->
<div class="page-wrap">

    <!-- LEFT COLUMN -->
    <div class="left-col">

        <!-- GALLERY -->
        <div class="gallery" style="margin-top:16px;">
            <div class="gallery-main" id="galleryMain" onclick="openLightbox(0)">
                <div class="img-placeholder" id="mainPhoto">🏸</div>
                <div class="photo-count" id="photoCount">📷 4 photos</div>
            </div>
            <div class="gallery-thumbs" id="thumbsRow"></div>
        </div>

        <!-- COURT INFO -->
        <div class="court-info-block">
            <div class="court-eyebrow" id="courtBadge">Available today</div>
            <div class="court-name" id="courtName">Court A – Hardcourt</div>
            <div class="court-tagline" id="courtTagline">The go-to indoor court for fast-paced games. Climate-controlled and fully equipped with professional-grade flooring and court lighting.</div>
        </div>

        <!-- FEATURES -->
        <div class="section-label">Amenities & features</div>
        <div class="features-grid" id="featuresGrid"></div>

        <!-- DESCRIPTION -->
        <div class="section-label">About this court</div>
        <div class="description-text" id="courtDesc"></div>

        <!-- RULES -->
        <div class="section-label">Court rules</div>
        <ul class="rules-list" id="courtRules"></ul>

        <!-- SCHEDULE -->
        <div class="section-label">Today's availability</div>
        <div class="schedule-preview">
            <div class="schedule-day" id="scheduleDay"></div>
            <div class="mini-slots" id="miniSlots"></div>
        </div>

    </div>

    <!-- RIGHT COLUMN (desktop only) -->
    <div class="right-col">
        <div class="booking-card">
            <div class="booking-price" id="bookingPrice">₱300 <span>/ hour</span></div>
            <hr class="booking-divider">
            <div class="booking-info-row"><span class="booking-info-key">Surface</span><span class="booking-info-val" id="bc-surface">Hardcourt</span></div>
            <div class="booking-info-row"><span class="booking-info-key">Setting</span><span class="booking-info-val" id="bc-setting">Indoor</span></div>
            <div class="booking-info-row"><span class="booking-info-key">Capacity</span><span class="booking-info-val" id="bc-capacity">Up to 10 players</span></div>
            <div class="booking-info-row"><span class="booking-info-key">Hours</span><span class="booking-info-val">6:00 AM – 10:00 PM</span></div>
            <div class="booking-info-row"><span class="booking-info-key">Min. booking</span><span class="booking-info-val">1 hour</span></div>
            <a href="/book?court=0" class="btn-book-now" id="bookBtn">Reserve this court →</a>
            <div class="availability-status" id="availStatus">5 slots available today</div>
            <div class="share-row">
                <button class="share-btn" onclick="copyLink()">🔗 Copy link</button>
                <button class="share-btn" onclick="shareNative()">↗ Share</button>
            </div>
        </div>
    </div>

</div>

<!-- MOBILE STICKY BOOKING BAR -->
<div class="mobile-book-bar">
    <div class="mobile-book-bar-inner">
        <div>
            <div class="mobile-book-price" id="mobilePrice">₱300 <small>/ hour</small></div>
            <div class="mobile-avail" id="mobileAvail">5 slots available</div>
        </div>
        <a href="/book?court=0" class="mobile-book-btn" id="mobileBookBtn">Reserve this court →</a>
    </div>
</div>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" onclick="closeLightboxOutside(event)">
    <div class="lightbox-inner">
        <button class="lightbox-close" onclick="closeLightbox()">✕</button>
        <button class="lb-nav lb-prev" onclick="lbNav(-1)">‹</button>
        <div id="lbContent"></div>
        <button class="lb-nav lb-next" onclick="lbNav(1)">›</button>
    </div>
</div>

<script>
const courts = [
    {
        name: "Court A – Hardcourt", badge: "Available today",
        tagline: "The go-to indoor court for fast-paced games. Climate-controlled and fully equipped with professional-grade flooring and court lighting.",
        price: 300, surface: "Hardcourt", setting: "Indoor", capacity: "Up to 10 players", slots: 5,
        photos: ["🏸","🏀","💡","❄️"],
        features: [
            { icon: "❄️", label: "Air-conditioned" }, { icon: "💡", label: "LED lighting" },
            { icon: "🚿", label: "Shower rooms" },    { icon: "🅿️", label: "Free parking" },
            { icon: "🎒", label: "Locker rooms" },    { icon: "🏋️", label: "Warm-up area" },
        ],
        desc: "Court A is our flagship indoor hardcourt — air-conditioned with professional sprung flooring that's gentle on your knees. Perfect for badminton, basketball, and futsal. The LED court lighting ensures consistent brightness across the entire surface, and the elevated spectator bench seats up to 20 guests.",
        rules: [
            "Proper court shoes required — no slippers or sandals.",
            "Maximum 10 players on court at a time.",
            "Food and drinks not allowed inside the court.",
            "Reservations are held for 10 minutes past booking time.",
            "Cancellations must be made at least 2 hours in advance.",
        ],
        bookedSlots: ["7:00–8:00 AM","10:00–11:00 AM","2:00–3:00 PM"],
    },
    {
        name: "Court B – Clay", badge: "Limited slots",
        tagline: "A classic outdoor clay surface that rewards precision and control. Ideal for tennis players of all levels with natural lighting in the morning.",
        price: 250, surface: "Clay", setting: "Outdoor", capacity: "Up to 4 players", slots: 3,
        photos: ["🎾","🌿","☀️","🏆"],
        features: [
            { icon: "☀️", label: "Natural lighting" }, { icon: "🌿", label: "Landscaped area" },
            { icon: "💧", label: "Water station" },    { icon: "🅿️", label: "Free parking" },
            { icon: "🪑", label: "Spectator seating" },{ icon: "🎾", label: "Ball rental" },
        ],
        desc: "Court B features a genuine clay surface imported for consistent ball bounce and reduced joint stress. The outdoor setting gives you open-air play with scenic green surroundings. Morning bookings catch the best natural light, while evening slots come with floodlights for extended play.",
        rules: [
            "Tennis shoes with flat or clay-specific soles required.",
            "Maximum 4 players (singles or doubles format).",
            "Smooth the clay surface after your session.",
            "Bookings cancelled under 1 hour before start are non-refundable.",
            "No pets allowed on the court grounds.",
        ],
        bookedSlots: ["6:00–7:00 AM","9:00–10:00 AM","5:00–6:00 PM"],
    },
    {
        name: "Court C – Synthetic", badge: "Most popular",
        tagline: "A covered all-weather synthetic surface built for multi-sport play. Rain or shine, your game goes on.",
        price: 280, surface: "Synthetic", setting: "Covered", capacity: "Up to 12 players", slots: 7,
        photos: ["🏐","🌧️","🏟️","⚽"],
        features: [
            { icon: "🌧️", label: "All-weather covered" }, { icon: "🏟️", label: "Spectator stand" },
            { icon: "🚿", label: "Shower rooms" },         { icon: "🅿️", label: "Free parking" },
            { icon: "🔊", label: "PA system" },            { icon: "⚽", label: "Multi-sport ready" },
        ],
        desc: "Court C is our largest and most versatile court — a cushioned synthetic surface covered by a steel roof structure so weather never disrupts your game. Accommodates volleyball, futsal, basketball, and more. Equipped with a PA system and full spectator stands, it's the best choice for group events and friendly tournaments.",
        rules: [
            "Non-marking sports shoes required at all times.",
            "Maximum 12 players per booking.",
            "No street shoes, cleats, or spiked footwear.",
            "Large group events (10+) require advance notice.",
            "Litter must be disposed of in designated bins after use.",
        ],
        bookedSlots: ["8:00–9:00 AM","1:00–2:00 PM","7:00–8:00 PM"],
    },
];

const amSlots  = ["6:00–7:00 AM","7:00–8:00 AM","8:00–9:00 AM","9:00–10:00 AM","10:00–11:00 AM","11:00 AM–12:00 PM"];
const pmSlots  = ["12:00–1:00 PM","1:00–2:00 PM","2:00–3:00 PM","3:00–4:00 PM","4:00–5:00 PM","5:00–6:00 PM","6:00–7:00 PM","7:00–8:00 PM","8:00–9:00 PM","9:00–10:00 PM"];
const allSlots = [...amSlots, ...pmSlots];

let currentCourt = 0, lbIndex = 0;

// Touch swipe on gallery
let touchStartX = 0;
document.getElementById('galleryMain').addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
document.getElementById('galleryMain').addEventListener('touchend', e => {
    const diff = touchStartX - e.changedTouches[0].screenX;
    if (Math.abs(diff) > 40) {
        const c = courts[currentCourt];
        const cur = [...document.querySelectorAll('.thumb')].findIndex(t => t.classList.contains('active'));
        const next = diff > 0 ? Math.min(cur + 1, c.photos.length - 1) : Math.max(cur - 1, 0);
        switchPhoto(next);
    }
}, { passive: true });

function loadCourt(idx) {
    currentCourt = idx;
    const c = courts[idx];

    document.querySelectorAll('.court-tab').forEach((t, i) => t.classList.toggle('active', i === idx));
    history.replaceState(null, '', `/courts/${idx}`);

    document.getElementById('bc-name').textContent    = c.name;
    document.getElementById('mainPhoto').textContent  = c.photos[0];
    document.getElementById('photoCount').textContent = '📷 ' + c.photos.length + ' photos';

    // Thumbs
    const tr = document.getElementById('thumbsRow');
    tr.innerHTML = '';
    c.photos.forEach((p, i) => {
        const d = document.createElement('div');
        d.className = 'thumb' + (i === 0 ? ' active' : '');
        d.innerHTML = `<div class="thumb-placeholder">${p}</div>`;
        d.onclick = () => switchPhoto(i);
        tr.appendChild(d);
    });

    document.getElementById('courtBadge').textContent   = c.badge;
    document.getElementById('courtName').textContent    = c.name;
    document.getElementById('courtTagline').textContent = c.tagline;
    document.getElementById('courtDesc').textContent    = c.desc;

    document.getElementById('featuresGrid').innerHTML = c.features.map(f =>
        `<div class="feature-item"><span class="feature-icon">${f.icon}</span>${f.label}</div>`
    ).join('');

    document.getElementById('courtRules').innerHTML = c.rules.map(r => `<li>${r}</li>`).join('');

    const now = new Date();
    document.getElementById('scheduleDay').textContent =
        now.toLocaleDateString('en-PH', { weekday: 'long', month: 'long', day: 'numeric' });
    document.getElementById('miniSlots').innerHTML = allSlots.map(s =>
        `<div class="mini-slot${c.bookedSlots.includes(s) ? ' taken' : ''}">${s}</div>`
    ).join('');

    // Desktop booking card
    document.getElementById('bookingPrice').innerHTML  = `₱${c.price} <span>/ hour</span>`;
    document.getElementById('bc-surface').textContent  = c.surface;
    document.getElementById('bc-setting').textContent  = c.setting;
    document.getElementById('bc-capacity').textContent = c.capacity;
    document.getElementById('availStatus').textContent = `${c.slots} slots available today`;
    document.getElementById('bookBtn').href            = `/book?court=${idx}`;

    // Mobile sticky bar
    document.getElementById('mobilePrice').innerHTML   = `₱${c.price} <small>/ hour</small>`;
    document.getElementById('mobileAvail').textContent = `${c.slots} slots available`;
    document.getElementById('mobileBookBtn').href      = `/book?court=${idx}`;
}

function switchPhoto(i) {
    const c = courts[currentCourt];
    document.getElementById('mainPhoto').textContent = c.photos[i];
    document.querySelectorAll('.thumb').forEach((t, ti) => t.classList.toggle('active', ti === i));
    lbIndex = i;
}

function openLightbox(i) {
    lbIndex = i; renderLb();
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
function closeLightboxOutside(e) { if (e.target === document.getElementById('lightbox')) closeLightbox(); }
function lbNav(dir) {
    const len = courts[currentCourt].photos.length;
    lbIndex = (lbIndex + dir + len) % len;
    renderLb();
}
function renderLb() {
    document.getElementById('lbContent').innerHTML =
        `<div class="lightbox-placeholder">${courts[currentCourt].photos[lbIndex]}</div>`;
}

// Lightbox touch swipe
let lbTouchX = 0;
document.getElementById('lightbox').addEventListener('touchstart', e => { lbTouchX = e.changedTouches[0].screenX; }, { passive: true });
document.getElementById('lightbox').addEventListener('touchend', e => {
    const diff = lbTouchX - e.changedTouches[0].screenX;
    if (Math.abs(diff) > 40) lbNav(diff > 0 ? 1 : -1);
}, { passive: true });

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => alert('Link copied!'));
}
function shareNative() {
    if (navigator.share) navigator.share({ title: courts[currentCourt].name, url: window.location.href });
    else copyLink();
}

// Init
function getInitialCourt() {
    const pathMatch = window.location.pathname.match(/\/courts\/(\d+)/);
    if (pathMatch) { const i = parseInt(pathMatch[1]); if (i >= 0 && i <= 2) return i; }
    const paramMatch = new URLSearchParams(window.location.search).get('court');
    if (paramMatch !== null) { const i = parseInt(paramMatch); if (i >= 0 && i <= 2) return i; }
    return 0;
}
loadCourt(getInitialCourt());
</script>
</body>
</html>
