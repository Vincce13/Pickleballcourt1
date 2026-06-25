<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reservation->reference_number }} – TDA Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        :root {
            --ink:#0f0f14;--ink2:#2a2a38;--muted:#6b6b80;--line:#e4e4ef;
            --surface:#f7f7fb;--white:#fff;--accent:#4f46e5;--accent-dark:#3730a3;
            --accent-light:#eef2ff;--green:#059669;--green-light:#ecfdf5;
            --amber:#d97706;--amber-light:#fffbeb;--red:#dc2626;--red-light:#fef2f2;
            --sidebar:240px;
        }
        body { font-family:'Inter',sans-serif; background:var(--surface); color:var(--ink); display:flex; min-height:100vh; }
        .sidebar { width:var(--sidebar); background:var(--ink2); color:#fff; display:flex; flex-direction:column; position:fixed; top:0; left:0; height:100vh; z-index:100; overflow-y:auto; }
        .sidebar-logo { padding:24px 20px 20px; font-family:'Syne',sans-serif; font-weight:800; font-size:18px; letter-spacing:3px; color:#fff; border-bottom:1px solid rgba(255,255,255,.1); }
        .sidebar-logo span { color:#818cf8; }
        .sidebar-section { padding:16px 12px 8px; font-size:10px; font-weight:700; letter-spacing:2px; color:rgba(255,255,255,.35); text-transform:uppercase; }
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; margin:2px 8px; font-size:14px; font-weight:500; color:rgba(255,255,255,.7); text-decoration:none; transition:all .15s; }
        .sidebar-link:hover { background:rgba(255,255,255,.08); color:#fff; }
        .sidebar-link.active { background:var(--accent); color:#fff; }
        .sidebar-link .icon { font-size:16px; width:20px; text-align:center; flex-shrink:0; }
        .sidebar-footer { margin-top:auto; padding:16px 12px; border-top:1px solid rgba(255,255,255,.1); }
        .sidebar-footer a { display:flex; align-items:center; gap:8px; font-size:13px; color:rgba(255,255,255,.5); text-decoration:none; padding:8px; border-radius:6px; transition:all .15s; }
        .sidebar-footer a:hover { color:#fff; background:rgba(255,255,255,.08); }
        .main { margin-left:var(--sidebar); flex:1; min-width:0; }
        .topbar { background:var(--white); border-bottom:1px solid var(--line); padding:0 32px; height:60px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; gap:12px; }
        .topbar-left { display:flex; align-items:center; gap:12px; }
        .topbar-back { color:var(--muted); text-decoration:none; font-size:13px; font-weight:500; display:flex; align-items:center; gap:4px; }
        .topbar-back:hover { color:var(--ink); }
        .topbar-title { font-size:16px; font-weight:700; }
        .topbar-badge { background:var(--accent-light); color:var(--accent); font-size:12px; font-weight:600; padding:4px 12px; border-radius:20px; }
        .content { padding:28px 32px; max-width:900px; }
        .flash { background:var(--green-light); border:1px solid #6ee7b7; border-radius:10px; padding:12px 16px; font-size:13px; color:#065f46; margin-bottom:20px; }
        /* GRID */
        .detail-grid { display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start; }
        /* CARD */
        .card { background:var(--white); border-radius:14px; border:1px solid var(--line); overflow:hidden; margin-bottom:20px; }
        .card-head { padding:16px 20px; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; align-items:center; }
        .card-head-title { font-size:14px; font-weight:700; }
        .card-body { padding:20px; }
        .info-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--line); font-size:13px; gap:12px; }
        .info-row:last-child { border-bottom:none; }
        .info-key { color:var(--muted); font-weight:500; flex-shrink:0; }
        .info-val { font-weight:600; text-align:right; word-break:break-word; }
        /* BADGES */
        .badge { display:inline-flex; align-items:center; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; }
        .badge-pending   { background:var(--amber-light); color:var(--amber); }
        .badge-confirmed { background:var(--green-light); color:var(--green); }
        .badge-cancelled { background:var(--red-light); color:var(--red); }
        .badge-completed { background:var(--accent-light); color:var(--accent); }
        .badge-paid      { background:var(--green-light); color:var(--green); }
        .badge-pending-pay { background:var(--amber-light); color:var(--amber); }
        /* ACTIONS */
        .action-title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--muted); margin-bottom:10px; }
        .action-btn { display:block; width:100%; padding:11px 16px; border-radius:10px; font-size:14px; font-weight:600; border:none; cursor:pointer; font-family:'Inter',sans-serif; margin-bottom:8px; text-align:center; transition:all .15s; }
        .btn-confirm  { background:var(--green-light); color:var(--green); border:1.5px solid #6ee7b7; }
        .btn-confirm:hover  { background:var(--green); color:#fff; }
        .btn-complete { background:var(--accent-light); color:var(--accent); border:1.5px solid #c7d2fe; }
        .btn-complete:hover { background:var(--accent); color:#fff; }
        .btn-cancel   { background:var(--red-light); color:var(--red); border:1.5px solid #fca5a5; }
        .btn-cancel:hover   { background:var(--red); color:#fff; }
        .btn-paid     { background:var(--green-light); color:var(--green); border:1.5px solid #6ee7b7; }
        .btn-paid:hover     { background:var(--green); color:#fff; }
        .btn-delete   { background:var(--red-light); color:var(--red); border:1.5px solid #fca5a5; margin-top:12px; }
        .btn-delete:hover   { background:var(--red); color:#fff; }
        .divider { border:none; border-top:1px solid var(--line); margin:16px 0; }
        /* REF BOX */
        .ref-box { background:var(--accent-light); border:1.5px solid #c7d2fe; border-radius:12px; padding:16px; margin-bottom:20px; text-align:center; }
        .ref-label { font-size:11px; font-weight:700; color:var(--accent); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }
        .ref-code { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; letter-spacing:3px; color:var(--ink); }
        @media (max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .main { margin-left:0; }
            .content { padding:16px; }
            .topbar { padding:0 16px; }
            .detail-grid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo">TDA<span>COURT</span></div>
    <div class="sidebar-section">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon">📊</span> Dashboard</a>
    <a href="{{ route('admin.reservations') }}" class="sidebar-link active"><span class="icon">📋</span> All Reservations</a>
    <div class="sidebar-section">Filter by Status</div>
    <a href="{{ route('admin.reservations', ['status'=>'pending']) }}"   class="sidebar-link"><span class="icon">⏳</span> Pending</a>
    <a href="{{ route('admin.reservations', ['status'=>'confirmed']) }}" class="sidebar-link"><span class="icon">✅</span> Confirmed</a>
    <a href="{{ route('admin.reservations', ['status'=>'cancelled']) }}" class="sidebar-link"><span class="icon">❌</span> Cancelled</a>
    <a href="{{ route('admin.reservations', ['status'=>'completed']) }}" class="sidebar-link"><span class="icon">🏁</span> Completed</a>
    <div class="sidebar-footer">
        <a href="/">← Back to site</a>
        <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:6px">
            @csrf
            <button type="submit" style="display:flex;align-items:center;gap:8px;width:100%;background:none;border:none;cursor:pointer;font-size:13px;color:rgba(255,255,255,.5);padding:8px;border-radius:6px;font-family:'Inter',sans-serif;transition:all .15s;"
                onmouseover="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'"
                onmouseout="this.style.background='none';this.style.color='rgba(255,255,255,.5)'">
                🚪 Sign out
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ route('admin.reservations') }}" class="topbar-back">← Back</a>
            <div class="topbar-title">{{ $reservation->reference_number }}</div>
            <span class="badge badge-{{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
        </div>
        <span class="topbar-badge">Admin Panel</span>
    </div>

    <div class="content">
        @if(session('success'))
        <div class="flash">✅ {{ session('success') }}</div>
        @endif

        <div class="detail-grid">

            <!-- LEFT: DETAILS -->
            <div>
                <!-- REFERENCE -->
                <div class="ref-box">
                    <div class="ref-label">Booking Reference</div>
                    <div class="ref-code">{{ $reservation->reference_number }}</div>
                </div>

                <!-- CLIENT INFO -->
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-title">👤 Client Information</div>
                    </div>
                    <div class="card-body">
                        <div class="info-row"><span class="info-key">Full Name</span><span class="info-val">{{ $reservation->full_name }}</span></div>
                        <div class="info-row"><span class="info-key">Mobile</span><span class="info-val">{{ $reservation->mobile_number }}</span></div>
                        <div class="info-row"><span class="info-key">Email</span><span class="info-val">{{ $reservation->email }}</span></div>
                    </div>
                </div>

                <!-- BOOKING DETAILS -->
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-title">📅 Booking Details</div>
                    </div>
                    <div class="card-body">
                        <div class="info-row"><span class="info-key">Court</span><span class="info-val">{{ $reservation->court_name }}</span></div>
                        <div class="info-row"><span class="info-key">Date</span><span class="info-val">{{ $reservation->booking_date->format('l, F j, Y') }}</span></div>
                        <div class="info-row"><span class="info-key">Time Slot</span><span class="info-val">{{ $reservation->time_slot }}</span></div>
                        <div class="info-row"><span class="info-key">Period</span><span class="info-val">{{ $reservation->time_period }}</span></div>
                        <div class="info-row"><span class="info-key">Amount</span><span class="info-val" style="color:var(--green);font-size:16px">₱{{ number_format($reservation->amount) }}</span></div>
                    </div>
                </div>

                <!-- PAYMENT -->
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-title">💳 Payment</div>
                        <span class="badge badge-{{ $reservation->payment_status === 'paid' ? 'paid' : 'pending-pay' }}">
                            {{ ucfirst($reservation->payment_status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="info-row"><span class="info-key">Method</span><span class="info-val">{{ $reservation->payment_method }}</span></div>
                        <div class="info-row"><span class="info-key">Status</span><span class="info-val">{{ ucfirst($reservation->payment_status) }}</span></div>
                        <div class="info-row"><span class="info-key">Created</span><span class="info-val">{{ $reservation->created_at->format('M d, Y h:i A') }}</span></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: ACTIONS -->
            <div>
                <!-- STATUS ACTIONS -->
                <div class="card">
                    <div class="card-head"><div class="card-head-title">Update Status</div></div>
                    <div class="card-body">
                        <div class="action-title">Booking Status</div>

                        @if($reservation->status !== 'confirmed')
                        <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="action-btn btn-confirm">✅ Mark as Confirmed</button>
                        </form>
                        @endif

                        @if($reservation->status !== 'completed')
                        <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="action-btn btn-complete">🏁 Mark as Completed</button>
                        </form>
                        @endif

                        @if($reservation->status !== 'cancelled')
                        <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}"
                              onsubmit="return confirm('Cancel this reservation?')">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="action-btn btn-cancel">❌ Cancel Reservation</button>
                        </form>
                        @endif

                        @if($reservation->status !== 'pending')
                        <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="pending">
                            <button type="submit" class="action-btn" style="background:var(--amber-light);color:var(--amber);border:1.5px solid #fcd34d">⏳ Set to Pending</button>
                        </form>
                        @endif

                        <hr class="divider">
                        <div class="action-title">Payment Status</div>

                        @if($reservation->payment_status !== 'paid')
                        <form method="POST" action="{{ route('admin.reservations.payment', $reservation) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="payment_status" value="paid">
                            <button type="submit" class="action-btn btn-paid">💰 Mark as Paid</button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.reservations.payment', $reservation) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="payment_status" value="pending">
                            <button type="submit" class="action-btn" style="background:var(--amber-light);color:var(--amber);border:1.5px solid #fcd34d">↩ Set Payment to Pending</button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- RECEIPT -->
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-title">📎 GCash Receipt</div>
                        @if($reservation->receipt_path)
                            <span class="badge badge-confirmed">Uploaded</span>
                        @else
                            <span style="font-size:12px;color:var(--muted)">Not yet uploaded</span>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($reservation->receipt_path)
                            <div style="text-align:center;margin-bottom:12px;">
                                <img src="{{ asset('storage/' . $reservation->receipt_path) }}"
                                     alt="GCash Receipt"
                                     style="max-width:100%;max-height:320px;border-radius:10px;border:1px solid var(--line);cursor:pointer;object-fit:contain;"
                                     onclick="this.requestFullscreen ? this.requestFullscreen() : window.open(this.src)">
                            </div>
                            <div style="font-size:12px;color:var(--muted);text-align:center;margin-bottom:12px;">
                                Uploaded {{ $reservation->receipt_uploaded_at?->format('M d, Y h:i A') }}
                                ({{ $reservation->receipt_uploaded_at?->diffForHumans() }})
                            </div>
                            <a href="{{ asset('storage/' . $reservation->receipt_path) }}"
                               download
                               style="display:block;width:100%;padding:10px;border-radius:9px;background:var(--accent-light);color:var(--accent);font-size:13px;font-weight:600;text-align:center;text-decoration:none;border:1.5px solid #c7d2fe;">
                                ⬇️ Download Receipt
                            </a>
                        @else
                            <div style="text-align:center;padding:24px;color:var(--muted);font-size:13px;">
                                <div style="font-size:32px;margin-bottom:8px;">📭</div>
                                Customer has not uploaded a receipt yet.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- DANGER ZONE -->
                <div class="card">
                    <div class="card-head"><div class="card-head-title" style="color:var(--red)">⚠️ Danger Zone</div></div>
                    <div class="card-body">
                        <p style="font-size:13px;color:var(--muted);margin-bottom:12px">Permanently delete this reservation. This cannot be undone.</p>
                        <form method="POST" action="{{ route('admin.reservations.destroy', $reservation) }}"
                              onsubmit="return confirm('Delete {{ $reservation->reference_number }} permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn btn-delete">🗑 Delete Reservation</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>