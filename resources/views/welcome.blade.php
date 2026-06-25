<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TDA Court – Reserve Your Court Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        :root {
            --ink:#0f0f14; --ink2:#2a2a38; --muted:#6b6b80;
            --line:#e4e4ef; --surface:#f7f7fb; --white:#fff;
            --accent:#4f46e5; --accent-dark:#3730a3; --accent-light:#eef2ff;
            --green:#059669; --green-light:#ecfdf5;
            --amber:#d97706; --amber-light:#fffbeb;
        }

        /* ── DARK MODE VARIABLES ── */
        html.dark {
            --ink:#f1f1f8; --ink2:#1a1a2e; --muted:#9ca3af;
            --line:#2a2a3e; --surface:#0d0d1a; --white:#1c1c2e;
            --accent:#818cf8; --accent-dark:#6366f1; --accent-light:#1e1b4b;
            --green:#34d399; --green-light:#064e3b;
            --amber:#fbbf24; --amber-light:#451a03;
        }
        html.dark body         { background:var(--surface); color:var(--ink); }
        html.dark nav          { background:rgba(28,28,46,.97); border-bottom-color:var(--line); }
        html.dark nav.scrolled { box-shadow:0 4px 24px rgba(0,0,0,.5); }
        html.dark .nav-drawer  { background:#1c1c2e; border-top-color:var(--line); }
        html.dark .nav-drawer a { color:var(--ink); }
        html.dark .nav-drawer a:hover { background:#2a2a3e; }
        html.dark .hero-visual { background:#13131f; border-color:var(--line); }
        html.dark .court-card  { background:#13131f; border-color:var(--line); }
        html.dark .hero-eyebrow { background:var(--accent-light); color:var(--accent); }
        html.dark .stats-section { background:#08080f; }
        html.dark .step-card   { background:#13131f; border-color:var(--line); }
        html.dark .step-card:hover { border-color:var(--accent); box-shadow:0 12px 40px rgba(129,140,248,.12); }
        html.dark .courts-section { background:#0d0d1a; border-color:var(--line); }
        html.dark .court-full-card { background:#1c1c2e; border-color:var(--line); }
        html.dark .court-full-card:hover { box-shadow:0 16px 48px rgba(0,0,0,.5); }
        html.dark .court-img.hardcourt { background:linear-gradient(135deg,#1e3a5f,#2d1f4e); }
        html.dark .court-img.clay      { background:linear-gradient(135deg,#3d2a00,#4d3800); }
        html.dark .court-img.synthetic { background:linear-gradient(135deg,#064e3b,#065f46); }
        html.dark .fun-section { background:linear-gradient(135deg,#1e1b4b,#2d1b69); }
        html.dark .cta-banner  { background:#08080f; }
        html.dark .cta-banner::before { background:rgba(129,140,248,.1); }
        html.dark .cta-banner::after  { background:rgba(99,102,241,.08); }
        html.dark footer       { background:#05050a; }
        html.dark .nav-hamburger span { background:var(--ink); }
        html.dark .btn-white   { background:#2a2a3e; color:var(--accent); }
        html.dark .btn-ghost   { color:var(--ink); }
        html.dark .ticker-wrap { background:#312e81; }
        html.dark .court-name, html.dark .step-name, html.dark .court-title { color:var(--ink); }
        html.dark .stat-num-big { color:#e0e7ff; }
        html.dark .avail-pill.open { background:#064e3b; color:#34d399; }
        html.dark .avail-pill.full { background:#450a0a; color:#f87171; }
        html.dark .available-badge { background:#064e3b; color:#34d399; }
        html.dark .available-badge.full { background:#450a0a; color:#f87171; }
        html.dark .float-btn   { background:var(--accent); box-shadow:0 8px 32px rgba(129,140,248,.35); }

        /* Smooth transitions on toggle */
        body, nav, .hero-visual, .court-card, .step-card,
        .court-full-card, .courts-section, .fun-section,
        .cta-banner, footer, .nav-drawer, .ticker-wrap,
        .court-img, .step-num, .section-title, h1, p {
            transition: background .35s ease, border-color .35s ease, color .25s ease, box-shadow .35s ease;
        }

        /* ── DARK TOGGLE BUTTON ── */
        .dark-toggle {
            width:38px; height:38px; border-radius:50%;
            border:1.5px solid var(--line);
            background:var(--surface);
            cursor:pointer; display:flex; align-items:center; justify-content:center;
            font-size:17px; transition:all .25s; flex-shrink:0;
            -webkit-tap-highlight-color:transparent; outline:none;
        }
        .dark-toggle:hover { border-color:var(--accent); background:var(--accent-light); transform:rotate(20deg) scale(1.1); }
        html.dark .dark-toggle { border-color:var(--line); background:var(--surface); }
        html.dark .dark-toggle:hover { background:#2a2a3e; }

        html { scroll-behavior:smooth; }
        body { font-family:'Inter',sans-serif; background:var(--white); color:var(--ink); line-height:1.6; overflow-x:hidden; }

        /* ── SCROLL REVEAL ── */
        .reveal {
            opacity:0; transform:translateY(36px);
            transition:opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1);
        }
        .reveal.visible { opacity:1; transform:translateY(0); }
        .reveal-left  { opacity:0; transform:translateX(-40px); transition:opacity .7s cubic-bezier(.22,1,.36,1),transform .7s cubic-bezier(.22,1,.36,1); }
        .reveal-right { opacity:0; transform:translateX(40px);  transition:opacity .7s cubic-bezier(.22,1,.36,1),transform .7s cubic-bezier(.22,1,.36,1); }
        .reveal-left.visible, .reveal-right.visible { opacity:1; transform:translate(0); }
        .reveal-delay-1 { transition-delay:.1s; }
        .reveal-delay-2 { transition-delay:.2s; }
        .reveal-delay-3 { transition-delay:.3s; }
        .reveal-delay-4 { transition-delay:.4s; }
        .reveal-delay-5 { transition-delay:.5s; }

        /* ── NAV ── */
        nav {
            position:sticky; top:0; z-index:200;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 48px; height:64px;
            background:rgba(255,255,255,.96); backdrop-filter:blur(12px);
            border-bottom:1px solid var(--line);
            transition:box-shadow .3s;
        }
        nav.scrolled { box-shadow:0 4px 24px rgba(0,0,0,.07); }
        .nav-logo { font-family:'Syne',sans-serif; font-weight:800; font-size:20px; letter-spacing:3px; color:var(--ink); text-decoration:none; }
        .nav-logo span { color:var(--accent); }
        .nav-links { display:flex; gap:32px; list-style:none; }
        .nav-links a { font-size:14px; color:var(--muted); text-decoration:none; font-weight:500; transition:color .15s; }
        .nav-links a:hover { color:var(--ink); }
        .nav-cta { background:var(--accent); color:#fff; padding:9px 22px; border-radius:8px; font-size:14px; font-weight:600; text-decoration:none; transition:background .15s, transform .15s; }
        .nav-cta:hover { background:var(--accent-dark); transform:translateY(-1px); }
        .nav-hamburger { display:none; background:none; border:none; cursor:pointer; padding:8px; flex-direction:column; gap:5px; }
        .nav-hamburger span { display:block; width:22px; height:2px; background:var(--ink); border-radius:2px; transition:all .2s; }
        .nav-hamburger.open span:nth-child(1) { transform:translateY(7px) rotate(45deg); }
        .nav-hamburger.open span:nth-child(2) { opacity:0; }
        .nav-hamburger.open span:nth-child(3) { transform:translateY(-7px) rotate(-45deg); }
        .nav-drawer { display:none; position:fixed; top:64px; left:0; right:0; bottom:0; background:var(--white); z-index:199; padding:24px; flex-direction:column; gap:8px; border-top:1px solid var(--line); }
        .nav-drawer.open { display:flex; }
        .nav-drawer a { font-size:16px; font-weight:600; color:var(--ink); text-decoration:none; padding:14px 16px; border-radius:10px; }
        .nav-drawer a:hover { background:var(--surface); }
        .nav-drawer .drawer-cta { background:var(--accent); color:#fff; text-align:center; margin-top:8px; border-radius:12px; }

        /* ── HERO ── */
        .hero {
            display:grid; grid-template-columns:1fr 1fr;
            align-items:center; gap:48px;
            max-width:1100px; margin:0 auto;
            padding:96px 48px 80px;
            min-height:calc(100vh - 64px);
        }
        .hero-eyebrow {
            display:inline-flex; align-items:center; gap:8px;
            background:var(--accent-light); color:var(--accent);
            font-size:12px; font-weight:600; letter-spacing:1px;
            text-transform:uppercase; padding:5px 14px; border-radius:20px; margin-bottom:20px;
            animation: pulse-dot 2s infinite;
        }
        .hero-eyebrow::before { content:''; width:7px; height:7px; border-radius:50%; background:var(--accent); }
        @keyframes pulse-dot {
            0%,100% { box-shadow:0 0 0 0 rgba(79,70,229,.3); }
            50%      { box-shadow:0 0 0 8px rgba(79,70,229,0); }
        }
        .hero h1 { font-family:'Syne',sans-serif; font-size:clamp(32px,5vw,58px); font-weight:800; line-height:1.1; letter-spacing:-1.5px; color:var(--ink); margin-bottom:20px; }
        .hero h1 em { font-style:normal; color:var(--accent); position:relative; }
        .hero h1 em::after { content:''; position:absolute; bottom:-2px; left:0; right:0; height:3px; background:var(--accent); border-radius:2px; transform:scaleX(0); transform-origin:left; animation:underline-in 1s .6s forwards cubic-bezier(.22,1,.36,1); }
        @keyframes underline-in { to { transform:scaleX(1); } }
        .hero p { font-size:17px; color:var(--muted); max-width:440px; margin-bottom:36px; line-height:1.75; }
        .hero-actions { display:flex; gap:14px; align-items:center; flex-wrap:wrap; }
        .btn-primary { background:var(--accent); color:#fff; padding:14px 28px; border-radius:10px; font-size:15px; font-weight:600; text-decoration:none; box-shadow:0 4px 16px rgba(79,70,229,.3); transition:background .15s,transform .15s,box-shadow .15s; display:inline-block; }
        .btn-primary:hover { background:var(--accent-dark); transform:translateY(-2px); box-shadow:0 8px 24px rgba(79,70,229,.4); }
        .btn-ghost { color:var(--ink); font-size:15px; font-weight:500; text-decoration:none; display:flex; align-items:center; gap:6px; transition:gap .15s; }
        .btn-ghost:hover { gap:10px; }
        .btn-ghost::after { content:'→'; }

        /* HERO VISUAL */
        .hero-visual { background:var(--surface); border-radius:20px; padding:24px; border:1px solid var(--line); box-shadow:0 8px 40px rgba(79,70,229,.07); }
        .hero-visual-label { font-size:12px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
        .live-dot { width:8px; height:8px; border-radius:50%; background:var(--green); animation:blink 1.5s infinite; }
        @keyframes blink { 0%,100%{opacity:1}50%{opacity:.3} }
        .court-card { background:var(--white); border-radius:14px; border:1px solid var(--line); padding:14px 16px; margin-bottom:10px; display:flex; align-items:center; gap:12px; transition:transform .2s,box-shadow .2s; cursor:default; }
        .court-card:last-child { margin-bottom:0; }
        .court-card:hover { transform:translateX(4px); box-shadow:0 4px 20px rgba(0,0,0,.06); }
        .court-icon { width:42px; height:42px; border-radius:10px; background:var(--accent-light); display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
        .court-info { flex:1; min-width:0; }
        .court-name { font-weight:600; font-size:14px; color:var(--ink); }
        .court-desc { font-size:12px; color:var(--muted); margin-top:2px; }
        .court-price { font-weight:700; color:var(--accent); font-size:14px; flex-shrink:0; }
        .available-badge { font-size:10px; font-weight:700; background:var(--green-light); color:var(--green); padding:2px 8px; border-radius:20px; margin-top:4px; display:inline-block; }
        .available-badge.full { background:#fef2f2; color:#dc2626; }

        /* ── TICKER STRIP ── */
        .ticker-wrap { background:var(--accent); overflow:hidden; padding:10px 0; }
        .ticker { display:flex; gap:0; animation:ticker-scroll 24s linear infinite; white-space:nowrap; }
        .ticker:hover { animation-play-state:paused; }
        @keyframes ticker-scroll { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
        .ticker-item { display:inline-flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#fff; padding:0 32px; opacity:.9; }
        .ticker-item::after { content:'•'; margin-left:32px; opacity:.4; }

        /* ── STATS CAROUSEL ── */
        .stats-section { background:var(--ink); padding:0; overflow:hidden; position:relative; }
        .stats-carousel { display:flex; transition:transform .6s cubic-bezier(.22,1,.36,1); }
        .stat-slide { flex:0 0 100%; display:grid; grid-template-columns:repeat(4,1fr); max-width:1100px; margin:0 auto; padding:48px; gap:0; }
        .stat-item { text-align:center; padding:0 24px; border-right:1px solid rgba(255,255,255,.1); }
        .stat-item:last-child { border-right:none; }
        .stat-num-big { font-family:'Syne',sans-serif; font-size:clamp(32px,5vw,52px); font-weight:800; color:#fff; line-height:1; }
        .stat-num-big span { color:#818cf8; }
        .stat-label-big { font-size:13px; color:rgba(255,255,255,.5); margin-top:8px; }
        .stat-dots { display:flex; justify-content:center; gap:8px; padding-bottom:28px; }
        .stat-dot { width:6px; height:6px; border-radius:50%; background:rgba(255,255,255,.25); cursor:pointer; transition:background .2s; }
        .stat-dot.active { background:#818cf8; width:20px; border-radius:3px; }

        /* ── HOW IT WORKS ── */
        .section { max-width:1100px; margin:0 auto; padding:80px 48px; }
        .section-label { font-size:12px; font-weight:600; letter-spacing:2px; text-transform:uppercase; color:var(--accent); margin-bottom:12px; }
        .section-title { font-family:'Syne',sans-serif; font-size:clamp(26px,3.5vw,40px); font-weight:800; color:var(--ink); line-height:1.15; margin-bottom:44px; }
        .steps-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
        .step-card {
            background:var(--surface); border-radius:16px; padding:28px;
            border:1px solid var(--line); position:relative; overflow:hidden;
            transition:border-color .2s,box-shadow .2s,transform .2s;
        }
        .step-card:hover { border-color:#c7d2fe; box-shadow:0 12px 40px rgba(79,70,229,.1); transform:translateY(-4px); }
        .step-card::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(79,70,229,.04),transparent); opacity:0; transition:opacity .2s; }
        .step-card:hover::before { opacity:1; }
        .step-num { font-family:'Syne',sans-serif; font-size:56px; font-weight:800; color:var(--line); line-height:1; position:absolute; top:12px; right:16px; }
        .step-icon { font-size:28px; margin-bottom:16px; display:block; }
        .step-name { font-weight:700; font-size:16px; color:var(--ink); margin-bottom:8px; }
        .step-desc { font-size:13px; color:var(--muted); line-height:1.65; }
        .step-connector { display:none; }

        /* ── COURTS ── */
        .courts-section { background:var(--surface); border-top:1px solid var(--line); border-bottom:1px solid var(--line); }
        .courts-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
        .court-full-card {
            background:var(--white); border-radius:18px; border:1px solid var(--line);
            overflow:hidden; transition:box-shadow .25s,transform .25s;
        }
        .court-full-card:hover { box-shadow:0 16px 48px rgba(0,0,0,.1); transform:translateY(-5px); }
        .court-img { height:140px; display:flex; align-items:center; justify-content:center; font-size:48px; position:relative; overflow:hidden; }
        .court-img::after { content:''; position:absolute; inset:0; background:linear-gradient(to bottom,transparent 60%,rgba(0,0,0,.04)); }
        .court-img.hardcourt { background:linear-gradient(135deg,#dbeafe,#ede9fe); }
        .court-img.clay      { background:linear-gradient(135deg,#fef3c7,#fde68a); }
        .court-img.synthetic { background:linear-gradient(135deg,#d1fae5,#a7f3d0); }
        .court-body { padding:20px; }
        .court-tag { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:1px; color:var(--muted); margin-bottom:4px; }
        .court-title { font-weight:700; font-size:16px; margin-bottom:6px; }
        .court-features { font-size:13px; color:var(--muted); margin-bottom:16px; line-height:1.5; }
        .court-footer { display:flex; justify-content:space-between; align-items:center; gap:10px; }
        .court-rate { font-weight:700; color:var(--accent); font-size:17px; }
        .court-rate small { font-size:12px; font-weight:400; color:var(--muted); }
        .btn-book { background:var(--accent); color:#fff; padding:9px 18px; border-radius:9px; font-size:13px; font-weight:600; text-decoration:none; transition:background .15s,transform .15s; }
        .btn-book:hover { background:var(--accent-dark); transform:translateY(-1px); }
        .btn-book-disabled { background:#f3f4f6; color:#9ca3af; padding:9px 18px; border-radius:9px; font-size:13px; font-weight:600; text-decoration:none; }
        .avail-pill { font-size:10px; font-weight:700; padding:3px 10px; border-radius:20px; }
        .avail-pill.open { background:var(--green-light); color:var(--green); }
        .avail-pill.full { background:#fef2f2; color:#dc2626; }

        /* ── TESTIMONIAL / FUN FACT ── */
        .fun-section { background:linear-gradient(135deg,var(--accent),#7c3aed); padding:60px 48px; text-align:center; }
        .fun-inner { max-width:700px; margin:0 auto; }
        .fun-quote { font-family:'Syne',sans-serif; font-size:clamp(22px,3vw,32px); font-weight:800; color:#fff; line-height:1.3; margin-bottom:16px; }
        .fun-quote em { font-style:normal; color:#c4b5fd; }
        .fun-sub { font-size:14px; color:rgba(255,255,255,.7); }
        .fun-icons { display:flex; justify-content:center; gap:16px; margin-bottom:20px; font-size:28px; }

        /* ── CTA BANNER ── */
        .cta-banner { background:var(--ink); color:#fff; padding:80px 48px; text-align:center; position:relative; overflow:hidden; }
        .cta-banner::before {
            content:''; position:absolute; width:400px; height:400px; border-radius:50%;
            background:rgba(79,70,229,.15); top:-100px; right:-80px; pointer-events:none;
        }
        .cta-banner::after {
            content:''; position:absolute; width:300px; height:300px; border-radius:50%;
            background:rgba(124,58,237,.1); bottom:-80px; left:-60px; pointer-events:none;
        }
        .cta-banner h2 { font-family:'Syne',sans-serif; font-size:clamp(28px,4vw,48px); font-weight:800; margin-bottom:14px; line-height:1.15; position:relative; }
        .cta-banner h2 em { font-style:normal; color:#818cf8; }
        .cta-banner p { font-size:16px; color:#a5b4fc; margin-bottom:36px; position:relative; }
        .btn-white { background:#fff; color:var(--accent); padding:15px 36px; border-radius:10px; font-size:16px; font-weight:700; text-decoration:none; display:inline-block; box-shadow:0 4px 20px rgba(255,255,255,.15); transition:transform .15s,box-shadow .15s; position:relative; }
        .btn-white:hover { transform:translateY(-2px); box-shadow:0 8px 32px rgba(255,255,255,.2); }

        /* ── FOOTER ── */
        footer { background:var(--ink2); color:#9ca3af; padding:36px 48px; display:flex; justify-content:space-between; align-items:center; font-size:13px; gap:16px; flex-wrap:wrap; }
        footer .logo { font-family:'Syne',sans-serif; font-weight:800; font-size:16px; letter-spacing:2px; color:#fff; }
        footer .logo span { color:#818cf8; }

        /* ── FLOATING BOOK BTN (mobile) ── */
        .float-btn { display:none; position:fixed; bottom:24px; left:50%; transform:translateX(-50%); background:var(--accent); color:#fff; padding:14px 36px; border-radius:50px; font-size:15px; font-weight:700; text-decoration:none; box-shadow:0 8px 32px rgba(79,70,229,.4); z-index:150; white-space:nowrap; transition:transform .15s; }
        .float-btn:hover { transform:translateX(-50%) translateY(-2px); }

        /* ── RESPONSIVE ── */
        @media (max-width:768px) {
            nav { padding:0 20px; }
            .nav-links,.nav-cta { display:none; }
            .nav-hamburger { display:flex; }
            .hero { grid-template-columns:1fr; padding:48px 20px 40px; gap:32px; min-height:auto; }
            .hero-visual { display:none; }
            .stat-slide { grid-template-columns:repeat(2,1fr); padding:36px 20px; gap:24px; }
            .stat-item { border-right:none; border-bottom:1px solid rgba(255,255,255,.1); padding-bottom:20px; }
            .stat-item:nth-child(3),.stat-item:last-child { border-bottom:none; }
            .section { padding:56px 20px; }
            .steps-grid { grid-template-columns:1fr; gap:14px; }
            .courts-grid { grid-template-columns:1fr; gap:16px; }
            .fun-section { padding:48px 20px; }
            .cta-banner { padding:56px 20px; }
            footer { flex-direction:column; text-align:center; padding:28px 20px; }
            .float-btn { display:block; }
        }
        @media (max-width:400px) {
            .hero h1 { font-size:26px; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav id="mainNav">
    <a href="/" class="nav-logo">TDA<span> COURT</span></a>
    <ul class="nav-links">
        <li><a href="#courts">Courts</a></li>
        <li><a href="#how-it-works">How it works</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
    <a href="/book" class="nav-cta">Book a court</a>
    <button class="dark-toggle" id="darkToggle" onclick="toggleDark()" title="Toggle dark mode" aria-label="Toggle dark mode">🌙</button>
    <button class="nav-hamburger" id="hamburger" onclick="toggleDrawer()">
        <span></span><span></span><span></span>
    </button>
</nav>

<div class="nav-drawer" id="navDrawer">
    <a href="#courts" onclick="closeDrawer()">🏟️ Courts</a>
    <a href="#how-it-works" onclick="closeDrawer()">📋 How it works</a>
    <a href="#contact" onclick="closeDrawer()">📞 Contact</a>
    <a href="/book" class="drawer-cta">Book a court →</a>
    <button onclick="toggleDark()" style="display:flex;align-items:center;gap:10px;background:var(--surface);border:1.5px solid var(--line);border-radius:10px;padding:14px 16px;font-size:15px;font-weight:600;color:var(--ink);cursor:pointer;margin-top:4px;width:100%;font-family:'Inter',sans-serif;" id="drawerDarkBtn">
        🌙 <span id="drawerDarkLabel">Switch to Dark Mode</span>
    </button>
</div>

<!-- HERO -->
<section class="hero">
    <div class="hero-text">
        <div class="hero-eyebrow reveal">Online court reservations</div>
        <h1 class="reveal reveal-delay-1">Reserve your <em>court</em>,<br>play on your schedule.</h1>
        <p class="reveal reveal-delay-2">TDA Court makes it easy to book a court in minutes — pick your slot, confirm your details, and you're set.</p>
        <div class="hero-actions reveal reveal-delay-3">
            <a href="/book" class="btn-primary">Book now</a>
            <a href="#courts" class="btn-ghost">View courts</a>
        </div>
    </div>
    <div class="hero-visual reveal-right">
        <div class="hero-visual-label">
            <div class="live-dot"></div>
            Available today — {{ now()->format('M d, Y') }}
        </div>
        @foreach($courts as $court)
        <div class="court-card">
            <div class="court-icon">{{ $court['emoji'] }}</div>
            <div class="court-info">
                <div class="court-name">{{ $court['name'] }}</div>
                <div class="court-desc">{{ $court['desc'] }}</div>
                @if($court['available_count'] > 0)
                    <div class="available-badge">{{ $court['available_count'] }} slot{{ $court['available_count'] > 1 ? 's' : '' }} open</div>
                @else
                    <div class="available-badge full">Fully booked</div>
                @endif
            </div>
            <div class="court-price">₱{{ $court['price'] }}<span style="font-size:11px;font-weight:400;color:var(--muted)">/hr</span></div>
        </div>
        @endforeach
    </div>
</section>

<!-- TICKER -->
<div class="ticker-wrap">
    <div class="ticker" id="ticker">
        <span class="ticker-item">🎾 Court A available now</span>
        <span class="ticker-item">🏸 Best pickleball courts in the area</span>
        <span class="ticker-item">📅 Book online in minutes</span>
        <span class="ticker-item">💚 GCash payments accepted</span>
        <span class="ticker-item">🏐 Multi-sport courts available</span>
        <span class="ticker-item">⚡ Instant booking confirmation</span>
        <span class="ticker-item">📍 Located in San Fernando</span>
        <span class="ticker-item">🎾 Court A available now</span>
        <span class="ticker-item">🏸 Best pickleball courts in the area</span>
        <span class="ticker-item">📅 Book online in minutes</span>
        <span class="ticker-item">💚 GCash payments accepted</span>
        <span class="ticker-item">🏐 Multi-sport courts available</span>
        <span class="ticker-item">⚡ Instant booking confirmation</span>
        <span class="ticker-item">📍 Located in San Fernando</span>
    </div>
</div>

<!-- STATS CAROUSEL -->
<div class="stats-section">
    <div class="stats-carousel" id="statsCarousel">
        <div class="stat-slide">
            <div class="stat-item">
                <div class="stat-num-big" data-target="3">0<span>+</span></div>
                <div class="stat-label-big">Courts available</div>
            </div>
            <div class="stat-item">
                <div class="stat-num-big"><span id="availNum">{{ $totalAvailableToday }}</span><span style="font-size:22px"> open</span></div>
                <div class="stat-label-big">Slots available today</div>
            </div>
            <div class="stat-item">
                <div class="stat-num-big" data-target="{{ $totalBookings }}">0<span>+</span></div>
                <div class="stat-label-big">Bookings completed</div>
            </div>
            <div class="stat-item">
                <div class="stat-num-big">6<span>am</span></div>
                <div class="stat-label-big">Opens daily</div>
            </div>
        </div>
        <div class="stat-slide">
            <div class="stat-item">
                <div class="stat-num-big">10<span>pm</span></div>
                <div class="stat-label-big">Last slot daily</div>
            </div>
            <div class="stat-item">
                <div class="stat-num-big">16<span>+</span></div>
                <div class="stat-label-big">Slots per court/day</div>
            </div>
            <div class="stat-item">
                <div class="stat-num-big">1<span>hr</span></div>
                <div class="stat-label-big">Minimum booking</div>
            </div>
            <div class="stat-item">
                <div class="stat-num-big">3<span>min</span></div>
                <div class="stat-label-big">To book online</div>
            </div>
        </div>
    </div>
    <div class="stat-dots">
        <div class="stat-dot active" onclick="goSlide(0)"></div>
        <div class="stat-dot" onclick="goSlide(1)"></div>
    </div>
</div>

<!-- HOW IT WORKS -->
<section class="section" id="how-it-works">
    <div class="section-label reveal">How it works</div>
    <div class="section-title reveal reveal-delay-1">Three steps to your game.</div>
    <div class="steps-grid">
        <div class="step-card reveal reveal-delay-1">
            <span class="step-num">1</span>
            <span class="step-icon">👤</span>
            <div class="step-name">Enter your details</div>
            <div class="step-desc">Fill in your name, mobile number, and email so we know who's coming in.</div>
        </div>
        <div class="step-card reveal reveal-delay-2">
            <span class="step-num">2</span>
            <span class="step-icon">📅</span>
            <div class="step-name">Pick a court & time</div>
            <div class="step-desc">Choose your court, date, and one or more time slots. Booked slots are shown in real-time.</div>
        </div>
        <div class="step-card reveal reveal-delay-3">
            <span class="step-num">3</span>
            <span class="step-icon">✅</span>
            <div class="step-name">Confirm & pay</div>
            <div class="step-desc">Review your booking, pay via GCash or at the counter, and upload your receipt.</div>
        </div>
    </div>
</section>

<!-- FUN SECTION -->
<div class="fun-section">
    <div class="fun-inner reveal">
        <div class="fun-icons">🏸 🎾 🏐 ⚽ 🏀</div>
        <div class="fun-quote">"The best games happen on the <em>best courts.</em>"</div>
        <div class="fun-sub">Reserve yours before someone else does — slots go fast on weekends!</div>
    </div>
</div>

<!-- COURTS -->
<div class="courts-section" id="courts">
    <div class="section">
        <div class="section-label reveal">Our courts</div>
        <div class="section-title reveal reveal-delay-1">Pick the surface that fits your game.</div>
        <div class="courts-grid">
            @php
                $courtMeta = [
                    0 => ['tag'=>'Indoor',  'img'=>'hardcourt', 'features'=>'Air-conditioned · Lights included · Best for badminton & basketball'],
                    1 => ['tag'=>'Outdoor', 'img'=>'clay',      'features'=>'Morning & evening slots · Soft on joints · Great for tennis'],
                    2 => ['tag'=>'Covered', 'img'=>'synthetic', 'features'=>'All-weather · Cushioned surface · Ideal for volleyball & futsal'],
                ];
            @endphp
            @foreach($courts as $court)
            @php $meta = $courtMeta[$court['id']]; @endphp
            <div class="court-full-card reveal reveal-delay-{{ $court['id'] + 1 }}">
                <div class="court-img {{ $meta['img'] }}">{{ $court['emoji'] }}</div>
                <div class="court-body">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <div class="court-tag">{{ $meta['tag'] }}</div>
                        @if($court['available_count'] > 0)
                            <span class="avail-pill open">{{ $court['available_count'] }} slots open today</span>
                        @else
                            <span class="avail-pill full">Fully booked today</span>
                        @endif
                    </div>
                    <div class="court-title">{{ $court['name'] }}</div>
                    <div class="court-features">{{ $meta['features'] }}</div>
                    <div class="court-footer">
                        <div class="court-rate">₱{{ $court['price'] }} <small>/hour</small></div>
                        @if($court['available_count'] > 0)
                            <a href="/courts/{{ $court['id'] }}" class="btn-book">View & Book</a>
                        @else
                            <a href="/courts/{{ $court['id'] }}" class="btn-book-disabled">View Court</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA BANNER -->
<div class="cta-banner">
    <h2 class="reveal">Ready to <em>play</em>?</h2>
    <p class="reveal reveal-delay-1">Slots fill up fast — especially on weekends. Book your court now and secure your time.</p>
    <a href="/book" class="btn-white reveal reveal-delay-2">Reserve a court →</a>
</div>

<!-- FOOTER -->
<footer id="contact">
    <div class="logo">TDA<span> COURT</span></div>
    <div>📍 San Fernando &nbsp;·&nbsp; 📞 0966 615 4780 &nbsp;·&nbsp; ✉️ hello@TDA.com</div>
    <div style="display:flex;align-items:center;gap:16px;">
        <span>© 2026 TDA. All rights reserved.</span>
        <a href="/admin/login" style="font-size:11px;color:rgba(255,255,255,.25);text-decoration:none;border:1px solid rgba(255,255,255,.15);padding:3px 10px;border-radius:20px;transition:all .15s;"
           onmouseover="this.style.color='rgba(255,255,255,.7)'"
           onmouseout="this.style.color='rgba(255,255,255,.25)'">Admin</a>
    </div>
</footer>

<!-- MOBILE FLOAT BUTTON -->
<a href="/book" class="float-btn">🎾 Book a court now</a>

<script>
// ── NAV SCROLL SHADOW ──
window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 10);
});

// ── NAV DRAWER ──
function toggleDrawer() {
    const d = document.getElementById('navDrawer');
    const b = document.getElementById('hamburger');
    d.classList.toggle('open'); b.classList.toggle('open');
    document.body.style.overflow = d.classList.contains('open') ? 'hidden' : '';
}
function closeDrawer() {
    document.getElementById('navDrawer').classList.remove('open');
    document.getElementById('hamburger').classList.remove('open');
    document.body.style.overflow = '';
}

// ── SCROLL REVEAL ──
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            revealObserver.unobserve(e.target);
        }
    });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal, .reveal-left, .reveal-right')
    .forEach(el => revealObserver.observe(el));

// ── STATS CAROUSEL (auto-rotate) ──
let currentSlide = 0;
const totalSlides = 2;

function goSlide(n) {
    currentSlide = n;
    document.getElementById('statsCarousel').style.transform = `translateX(-${n * 100}%)`;
    document.querySelectorAll('.stat-dot').forEach((d, i) => d.classList.toggle('active', i === n));
}

setInterval(() => goSlide((currentSlide + 1) % totalSlides), 3500);

// ── DARK MODE ──
function toggleDark() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('tdaDark', isDark ? '1' : '0');
    updateDarkUI(isDark);
}

function updateDarkUI(isDark) {
    const btn         = document.getElementById('darkToggle');
    const drawerBtn   = document.getElementById('drawerDarkLabel');
    if (btn)       btn.textContent       = isDark ? '☀️' : '🌙';
    if (drawerBtn) drawerBtn.textContent = isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode';
}

// Load saved preference on page load
(function() {
    const saved = localStorage.getItem('tdaDark');
    // Also respect system preference if no saved preference
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const shouldBeDark = saved !== null ? saved === '1' : prefersDark;
    if (shouldBeDark) {
        document.documentElement.classList.add('dark');
        updateDarkUI(true);
    }
})();

// ── COUNT-UP ANIMATION ──
function countUp(el, target, duration = 1500) {
    let start = 0;
    const step = target / (duration / 16);
    const timer = setInterval(() => {
        start = Math.min(start + step, target);
        const span = el.querySelector('span');
        const suffix = span ? span.outerHTML : '';
        el.innerHTML = Math.floor(start) + suffix;
        if (start >= target) clearInterval(timer);
    }, 16);
}

const countObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            const target = parseInt(e.target.dataset.target);
            if (!isNaN(target)) countUp(e.target, target);
            countObserver.unobserve(e.target);
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('[data-target]').forEach(el => countObserver.observe(el));
</script>
</body>
</html>