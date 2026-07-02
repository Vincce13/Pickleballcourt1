<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – TDA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink: #0f0f14; --ink2: #2a2a38; --muted: #6b6b80;
            --line: #e4e4ef; --surface: #f7f7fb; --white: #fff;
            --accent: #4f46e5; --accent-dark: #3730a3; --accent-light: #eef2ff;
            --green: #059669; --green-light: #ecfdf5;
            --amber: #d97706; --amber-light: #fffbeb;
            --red: #dc2626; --red-light: #fef2f2;
            --sidebar: 240px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--surface); color: var(--ink); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar { width: var(--sidebar); background: var(--ink2); color: #fff; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; z-index: 100; overflow-y: auto; }
        .sidebar-logo { padding: 24px 20px 20px; font-family: 'Syne', sans-serif; font-weight: 800; font-size: 18px; letter-spacing: 3px; color: #fff; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-logo span { color: #818cf8; }
        .sidebar-section { padding: 16px 12px 8px; font-size: 10px; font-weight: 700; letter-spacing: 2px; color: rgba(255,255,255,.35); text-transform: uppercase; }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; margin: 2px 8px; font-size: 14px; font-weight: 500; color: rgba(255,255,255,.7); text-decoration: none; transition: all .15s; }
        .sidebar-link:hover { background: rgba(255,255,255,.08); color: #fff; }
        .sidebar-link.active { background: var(--accent); color: #fff; }
        .sidebar-link .icon { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-footer { margin-top: auto; padding: 16px 12px; border-top: 1px solid rgba(255,255,255,.1); }
        .sidebar-footer a { display: flex; align-items: center; gap: 8px; font-size: 13px; color: rgba(255,255,255,.5); text-decoration: none; padding: 8px; border-radius: 6px; transition: all .15s; }
        .sidebar-footer a:hover { color: #fff; background: rgba(255,255,255,.08); }

        /* MAIN */
        .main { margin-left: var(--sidebar); flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .topbar { background: var(--white); border-bottom: 1px solid var(--line); padding: 0 32px; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar-title { font-size: 16px; font-weight: 700; color: var(--ink); }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-badge { background: var(--accent-light); color: var(--accent); font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; }
        .content { padding: 28px 32px; flex: 1; }
        .flash { background: var(--green-light); border: 1px solid #6ee7b7; border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #065f46; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }

        /* STATS GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card { background: var(--white); border-radius: 14px; border: 1px solid var(--line); padding: 20px; }
        .stat-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
        .stat-card-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .stat-card-label { font-size: 12px; color: var(--muted); font-weight: 500; margin-bottom: 4px; }
        .stat-card-value { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: var(--ink); line-height: 1; }
        .stat-card-sub { font-size: 12px; color: var(--muted); margin-top: 6px; }

        /* REVENUE CARD — special */
        .revenue-card { background: var(--white); border-radius: 14px; border: 1px solid var(--line); padding: 20px; }
        .revenue-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
        .revenue-tabs { display: flex; gap: 4px; background: var(--surface); border-radius: 8px; padding: 3px; border: 1px solid var(--line); }
        .revenue-tab { padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; border: none; background: transparent; color: var(--muted); font-family: 'Inter', sans-serif; transition: all .15s; }
        .revenue-tab.active { background: var(--white); color: var(--accent); box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .revenue-tab:hover:not(.active) { color: var(--ink); }
        .revenue-amount { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: var(--green); line-height: 1; margin-bottom: 4px; transition: opacity .2s; }
        .revenue-count { font-size: 12px; color: var(--muted); }
        .revenue-period-label { font-size: 11px; color: var(--accent); font-weight: 600; margin-top: 6px; }

        /* SECTION TITLE */
        .section-title { font-size: 15px; font-weight: 700; color: var(--ink); margin-bottom: 14px; }

        /* TABLE */
        .table-wrap { background: var(--white); border-radius: 14px; border: 1px solid var(--line); overflow: hidden; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: var(--surface); padding: 11px 16px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); text-align: left; border-bottom: 1px solid var(--line); white-space: nowrap; }
        tbody td { padding: 13px 16px; font-size: 13px; color: var(--ink); border-bottom: 1px solid var(--line); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--surface); }

        /* BADGES */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap; }
        .badge-pending   { background: var(--amber-light); color: var(--amber); }
        .badge-confirmed { background: var(--green-light); color: var(--green); }
        .badge-cancelled { background: var(--red-light); color: var(--red); }
        .badge-completed { background: var(--accent-light); color: var(--accent); }
        .badge-paid      { background: var(--green-light); color: var(--green); }

        /* TWO-COL */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .today-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid var(--line); font-size: 13px; }
        .today-item:last-child { border-bottom: none; }
        .today-time { font-weight: 700; color: var(--ink); min-width: 120px; }
        .today-name { color: var(--muted); }
        .today-court { font-size: 11px; color: var(--accent); font-weight: 600; }
        .view-link { color: var(--accent); font-size: 12px; font-weight: 600; text-decoration: none; }
        .view-link:hover { text-decoration: underline; }

        /* REVENUE ICON PICKER */
        .rpick-item { padding:10px 16px; font-size:13px; font-weight:500; cursor:pointer; color:var(--ink); transition:background .1s; }
        .rpick-item:hover { background:var(--accent-light); color:var(--accent); }
        .rpick-item.active { background:var(--accent-light); color:var(--accent); font-weight:700; }

        @media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } .two-col { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main { margin-left: 0; } .stats-grid { grid-template-columns: 1fr 1fr; } .content { padding: 20px 16px; } .topbar { padding: 0 16px; } }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">TDA<span></span></div>
    <div class="sidebar-section">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link active"><span class="icon">📊</span> Dashboard</a>
    <a href="{{ route('admin.reservations') }}" class="sidebar-link"><span class="icon">📋</span> Reservations</a>
    <a href="{{ route('admin.blocked-slots') }}" class="sidebar-link"><span class="icon">🚧</span> Blocked Slots</a>
    <div class="sidebar-section">Filter by Status</div>
    <a href="{{ route('admin.reservations', ['status' => 'pending']) }}" class="sidebar-link"><span class="icon">⏳</span> Pending</a>
    <a href="{{ route('admin.reservations', ['status' => 'confirmed']) }}" class="sidebar-link"><span class="icon">✅</span> Confirmed</a>
    <a href="{{ route('admin.reservations', ['status' => 'cancelled']) }}" class="sidebar-link"><span class="icon">❌</span> Cancelled</a>
    <a href="{{ route('admin.reservations', ['status' => 'completed']) }}" class="sidebar-link"><span class="icon">🏁</span> Completed</a>
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
        <div class="topbar-title">Dashboard</div>
        <div class="topbar-right">
            <span class="topbar-badge">Admin Panel</span>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
        <div class="flash">✅ {{ session('success') }}</div>
        @endif

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-card-label">Total Reservations</div>
                        <div class="stat-card-value">{{ $stats['total'] }}</div>
                    </div>
                    <div class="stat-card-icon" style="background:#eef2ff">📋</div>
                </div>
                <div class="stat-card-sub">All time bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-card-label">Pending</div>
                        <div class="stat-card-value" style="color:var(--amber)">{{ $stats['pending'] }}</div>
                    </div>
                    <div class="stat-card-icon" style="background:#fffbeb">⏳</div>
                </div>
                <div class="stat-card-sub">Awaiting confirmation</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-card-label">Confirmed</div>
                        <div class="stat-card-value" style="color:var(--green)">{{ $stats['confirmed'] }}</div>
                    </div>
                    <div class="stat-card-icon" style="background:var(--green-light)">✅</div>
                </div>
                <div class="stat-card-sub">Ready to play</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-card-label">Today's Bookings</div>
                        <div class="stat-card-value" style="color:var(--accent)">{{ $stats['today'] }}</div>
                    </div>
                    <div class="stat-card-icon" style="background:var(--accent-light)">📅</div>
                </div>
                <div class="stat-card-sub">{{ now()->format('M d, Y') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-card-label">Cancelled</div>
                        <div class="stat-card-value" style="color:var(--red)">{{ $stats['cancelled'] }}</div>
                    </div>
                    <div class="stat-card-icon" style="background:var(--red-light)">❌</div>
                </div>
                <div class="stat-card-sub">All time cancellations</div>
            </div>

            <!-- ── REVENUE CARD WITH ICON DROPDOWN ── -->
            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-card-label">💰 Revenue</div>
                        <div class="revenue-amount" id="revenueAmount">₱{{ number_format($stats['revenue_today']) }}</div>
                    </div>
                    <!-- Clickable icon as dropdown trigger -->
                    <div style="position:relative;">
                        <div id="revenueIconBtn" onclick="toggleRevenuePicker(event)"
                            style="width:40px;height:40px;border-radius:10px;background:var(--green-light);display:flex;align-items:center;justify-content:center;font-size:18px;cursor:pointer;transition:all .15s;user-select:none;"
                            title="Filter by period">
                            💰
                        </div>
                        <!-- Popover menu -->
                        <div id="revenuePicker" style="display:none;position:absolute;top:48px;right:0;background:var(--white);border:1.5px solid var(--line);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:200;min-width:130px;overflow:hidden;">
                            <div class="rpick-item active" onclick="switchRevenue('today', this)">📅 Today</div>
                            <div class="rpick-item" onclick="switchRevenue('week', this)">📆 This Week</div>
                            <div class="rpick-item" onclick="switchRevenue('month', this)">🗓️ This Month</div>
                            <div class="rpick-item" onclick="switchRevenue('all', this)">📊 All Time</div>
                        </div>
                    </div>
                </div>
                <div style="margin-top:4px;display:flex;align-items:flex-start;justify-content:space-between;">
                    <div>
                        <div class="stat-card-sub" id="revenueCount">{{ $stats['revenue_today_count'] }} paid booking(s)</div>
                        <div style="font-size:11px;color:var(--accent);font-weight:600;margin-top:3px" id="revenuePeriod">{{ now()->format('M d, Y') }}</div>
                    </div>
                    <button onclick="openRevenueModal()" title="View Breakdown"
                        style="padding:5px 10px;border-radius:7px;border:1.5px solid var(--line);background:var(--surface);color:var(--muted);font-size:11px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:all .15s;white-space:nowrap;"
                        onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)';this.style.background='var(--accent-light)'"
                        onmouseout="this.style.borderColor='var(--line)';this.style.color='var(--muted)';this.style.background='var(--surface)'">
                        📋 Report
                    </button>
                </div>
            </div>
        </div>

        <!-- ── REVENUE BREAKDOWN MODAL ── -->
        <div id="revenueModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
            <div style="background:var(--white);border-radius:16px;border:1px solid var(--line);width:90%;max-width:580px;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 24px 60px rgba(0,0,0,.18);animation:slideUpModal .25s ease;">
                <!-- Modal Header -->
                <div style="padding:20px 24px;border-bottom:1px solid var(--line);">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                        <div>
                            <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:16px;color:var(--ink)" id="modalTitle">Today's Paid Bookings</div>
                            <div style="font-size:12px;color:var(--muted);margin-top:2px" id="modalPeriod">{{ now()->format('M d, Y') }}</div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:20px;color:var(--green)" id="modalAmount">₱{{ number_format($stats['revenue_today']) }}</div>
                            <button onclick="closeRevenueModal()" style="width:32px;height:32px;border-radius:8px;border:1px solid var(--line);background:var(--surface);cursor:pointer;font-size:18px;color:var(--muted);line-height:1;">✕</button>
                        </div>
                    </div>
                    <!-- Month Navigator (only shown when viewing month-based report) -->
                    <div id="monthNav" style="display:none;align-items:center;justify-content:center;gap:14px;background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:6px 10px;margin-bottom:12px;">
                        <button onclick="navigateMonth(-1)" style="width:26px;height:26px;border-radius:6px;border:none;background:var(--white);border:1px solid var(--line);cursor:pointer;font-size:13px;color:var(--ink);display:flex;align-items:center;justify-content:center;">‹</button>
                        <span id="monthNavLabel" style="font-size:13px;font-weight:700;min-width:120px;text-align:center;">{{ now()->format('F Y') }}</span>
                        <button onclick="navigateMonth(1)" id="monthNavNext" style="width:26px;height:26px;border-radius:6px;border:none;background:var(--white);border:1px solid var(--line);cursor:pointer;font-size:13px;color:var(--ink);display:flex;align-items:center;justify-content:center;">›</button>
                    </div>
                    <!-- Action Buttons -->
                    <div style="display:flex;gap:8px;">
                        <button onclick="printRevenue()" style="flex:1;padding:8px 12px;border-radius:8px;border:1.5px solid var(--line);background:var(--surface);color:var(--ink);font-size:12px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .15s;"
                            onmouseover="this.style.borderColor='var(--ink)'"
                            onmouseout="this.style.borderColor='var(--line)'">
                            🖨️ Print
                        </button>
                        <button onclick="downloadCSV()" style="flex:1;padding:8px 12px;border-radius:8px;border:1.5px solid var(--green);background:var(--green-light);color:var(--green);font-size:12px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .15s;"
                            onmouseover="this.style.background='var(--green)';this.style.color='#fff'"
                            onmouseout="this.style.background='var(--green-light)';this.style.color='var(--green)'">
                            ⬇️ Download CSV
                        </button>
                    </div>
                </div>
                <!-- Modal Body -->
                <div style="overflow-y:auto;flex:1;padding:8px 0;" id="modalBody">
                    <!-- filled by JS -->
                </div>
            </div>
        </div>

        <!-- RECENT + TODAY -->
        <div class="two-col">
            <div>
                <div class="section-title">Recent Reservations</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Name</th>
                                <th>Court</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent as $r)
                            <tr>
                                <td style="font-family:monospace;font-weight:600;color:var(--accent)">{{ $r->reference_number }}</td>
                                <td>{{ $r->full_name }}</td>
                                <td style="font-size:12px;color:var(--muted)">{{ $r->court_name }}</td>
                                <td><span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                                <td><a href="{{ route('admin.reservations.show', $r) }}" class="view-link">View →</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:24px">No reservations yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="section-title">Today's Schedule — {{ now()->format('M d, Y') }}</div>
                <div class="table-wrap">
                    @forelse($todayBookings as $b)
                    <div class="today-item">
                        <div>
                            <div class="today-time">{{ $b->time_slots_display }}</div>
                            <div class="today-name">{{ $b->full_name }}</div>
                        </div>
                        <div style="text-align:right">
                            <div class="today-court">{{ $b->court_name }}</div>
                            <span class="badge badge-{{ $b->status }}" style="margin-top:4px">{{ ucfirst($b->status) }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="padding:32px;text-align:center;color:var(--muted);font-size:13px">No bookings scheduled for today.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideUpModal { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>

<script>
const revenueData = {
    today: {
        amount: {{ $stats['revenue_today'] }},
        count:  {{ $stats['revenue_today_count'] }},
        label:  '{{ now()->format("M d, Y") }}',
        title:  "Today's Paid Bookings",
        items:  @json($stats['revenue_today_items']),
    },
    week: {
        amount: {{ $stats['revenue_week'] }},
        count:  {{ $stats['revenue_week_count'] }},
        label:  '{{ now()->startOfWeek()->format("M d") }} – {{ now()->endOfWeek()->format("M d, Y") }}',
        title:  "This Week's Paid Bookings",
        items:  @json($stats['revenue_week_items']),
    },
    month: {
        amount: {{ $stats['revenue_month'] }},
        count:  {{ $stats['revenue_month_count'] }},
        label:  '{{ now()->format("F Y") }}',
        title:  '{{ now()->format("F Y") }} Paid Bookings',
        items:  @json($stats['revenue_month_items']),
    },
    all: {
        amount: {{ $stats['revenue'] }},
        count:  {{ $stats['revenue_all_count'] }},
        label:  'All time',
        title:  'All Paid Bookings',
        items:  @json($stats['revenue_all_items']),
    },
};

let currentPeriod = 'today';

function toggleRevenuePicker(e) {
    e.stopPropagation();
    const picker = document.getElementById('revenuePicker');
    const btn    = document.getElementById('revenueIconBtn');
    const isOpen = picker.style.display === 'block';
    picker.style.display = isOpen ? 'none' : 'block';
    btn.style.background = isOpen ? 'var(--green-light)' : 'var(--accent-light)';
}

// Close picker when clicking anywhere else
document.addEventListener('click', function() {
    document.getElementById('revenuePicker').style.display = 'none';
    document.getElementById('revenueIconBtn').style.background = 'var(--green-light)';
});

function switchRevenue(period, el) {
    currentPeriod = period;
    const d = revenueData[period];

    // Update active state in picker
    document.querySelectorAll('.rpick-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');

    // Close picker
    document.getElementById('revenuePicker').style.display = 'none';
    document.getElementById('revenueIconBtn').style.background = 'var(--green-light)';

    // Update card
    const amountEl = document.getElementById('revenueAmount');
    amountEl.style.opacity = '0';
    setTimeout(() => { amountEl.textContent = '₱' + Number(d.amount).toLocaleString(); amountEl.style.opacity = '1'; }, 150);
    document.getElementById('revenueCount').textContent  = d.count + ' paid booking(s)';
    document.getElementById('revenuePeriod').textContent = d.label;
}

let viewingYear  = {{ now()->year }};
let viewingMonth = {{ now()->month }}; // 1-12

let lastRenderedData = null;

function renderModalData(d) {
    lastRenderedData = d;
    document.getElementById('modalTitle').textContent  = d.title;
    document.getElementById('modalPeriod').textContent = d.label;
    document.getElementById('modalAmount').textContent = '₱' + Number(d.amount).toLocaleString();

    const body = document.getElementById('modalBody');
    body.innerHTML = '';

    if (d.items.length === 0) {
        body.innerHTML = '<div style="padding:32px;text-align:center;font-size:13px;color:var(--muted)">No paid bookings for this period.</div>';
        return;
    }

    body.innerHTML = `
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:8px;padding:10px 24px;background:var(--surface);font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid var(--line);">
            <div>Reference</div><div>Client</div><div>Court</div><div style="text-align:right">Amount</div>
        </div>`;
    d.items.forEach(item => {
        const row = document.createElement('div');
        row.style.cssText = 'display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:8px;padding:12px 24px;border-bottom:1px solid var(--line);font-size:13px;align-items:center;';
        row.innerHTML = `
            <div style="font-family:monospace;font-weight:700;color:var(--accent);font-size:12px">${item.ref}</div>
            <div style="font-weight:500">${item.name}</div>
            <div style="font-size:12px;color:var(--muted)">${item.court}</div>
            <div style="font-weight:700;color:var(--green);text-align:right">₱${Number(item.amount).toLocaleString()}</div>`;
        body.appendChild(row);
    });

    const total = document.createElement('div');
    total.style.cssText = 'display:flex;justify-content:space-between;padding:14px 24px;font-weight:700;font-size:14px;border-top:2px solid var(--line);background:var(--surface);';
    total.innerHTML = `<span>Total (${d.count} booking${d.count !== 1 ? 's' : ''})</span><span style="color:var(--green)">₱${Number(d.amount).toLocaleString()}</span>`;
    body.appendChild(total);
}

function openRevenueModal() {
    const monthNav = document.getElementById('monthNav');

    if (currentPeriod === 'month') {
        // Reset to current month each time modal is freshly opened for "month"
        viewingYear  = {{ now()->year }};
        viewingMonth = {{ now()->month }};
        monthNav.style.display = 'flex';
        updateMonthNavLabel();
        renderModalData(revenueData.month); // initial = current month data we already have
    } else {
        monthNav.style.display = 'none';
        renderModalData(revenueData[currentPeriod]);
    }

    const modal = document.getElementById('revenueModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function updateMonthNavLabel() {
    const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    document.getElementById('monthNavLabel').textContent = months[viewingMonth - 1] + ' ' + viewingYear;

    // Disable "next" if viewing current month or later
    const now = new Date();
    const isCurrentOrFuture = (viewingYear > now.getFullYear()) ||
        (viewingYear === now.getFullYear() && viewingMonth >= (now.getMonth() + 1));
    document.getElementById('monthNavNext').style.opacity = isCurrentOrFuture ? '0.35' : '1';
    document.getElementById('monthNavNext').style.pointerEvents = isCurrentOrFuture ? 'none' : 'auto';
}

async function navigateMonth(direction) {
    viewingMonth += direction;
    if (viewingMonth < 1)  { viewingMonth = 12; viewingYear--; }
    if (viewingMonth > 12) { viewingMonth = 1;  viewingYear++; }
    updateMonthNavLabel();

    const body = document.getElementById('modalBody');
    body.innerHTML = '<div style="padding:40px;text-align:center;font-size:13px;color:var(--muted)">⏳ Loading…</div>';

    try {
        const res  = await fetch(`{{ route('admin.dashboard.revenue-month') }}?year=${viewingYear}&month=${viewingMonth}`);
        const data = await res.json();
        renderModalData(data);
    } catch (e) {
        body.innerHTML = '<div style="padding:40px;text-align:center;font-size:13px;color:var(--red)">⚠️ Failed to load data. Please try again.</div>';
    }
}

function closeRevenueModal() {
    document.getElementById('revenueModal').style.display = 'none';
    document.body.style.overflow = '';
}

function printRevenue() {
    const d        = lastRenderedData || revenueData[currentPeriod];
    const rows     = d.items.map(item =>
        `<tr>
            <td>${item.ref}</td>
            <td>${item.name}</td>
            <td>${item.court}</td>
            <td style="text-align:right;font-weight:700;">₱${Number(item.amount).toLocaleString()}</td>
        </tr>`
    ).join('');

    const win = window.open('', '_blank');
    win.document.write(`
        <!DOCTYPE html><html><head>
        <title>Revenue Report – ${d.title}</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 32px; color: #0f0f14; }
            h1 { font-size: 20px; margin-bottom: 4px; }
            .sub { font-size: 13px; color: #6b6b80; margin-bottom: 24px; }
            table { width: 100%; border-collapse: collapse; font-size: 13px; }
            th { background: #f7f7fb; padding: 10px 14px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #6b6b80; border-bottom: 2px solid #e4e4ef; }
            td { padding: 11px 14px; border-bottom: 1px solid #e4e4ef; }
            .total-row td { font-weight: 700; font-size: 14px; border-top: 2px solid #e4e4ef; border-bottom: none; background: #f7f7fb; }
            .logo { font-weight: 900; font-size: 18px; letter-spacing: 3px; margin-bottom: 20px; }
            .logo span { color: #4f46e5; }
            @media print { body { padding: 0; } }
        </style>
        </head><body>
        <div class="logo">TDA<span>COURT</span></div>
        <h1>💰 Revenue Report</h1>
        <div class="sub">${d.title} &nbsp;·&nbsp; ${d.label} &nbsp;·&nbsp; ${d.count} paid booking(s)</div>
        <table>
            <thead><tr><th>Reference</th><th>Client</th><th>Court</th><th style="text-align:right">Amount</th></tr></thead>
            <tbody>${rows}</tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3">Total (${d.count} booking${d.count !== 1 ? 's' : ''})</td>
                    <td style="text-align:right;color:#059669;">₱${Number(d.amount).toLocaleString()}</td>
                </tr>
            </tfoot>
        </table>
        <div style="margin-top:32px;font-size:11px;color:#9ca3af;">Generated on ${new Date().toLocaleString()} · TDA Court Admin</div>
        </body></html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => { win.print(); }, 400);
}

function downloadCSV() {
    const d = lastRenderedData || revenueData[currentPeriod];
    const headers = ['Reference', 'Client Name', 'Court', 'Amount (PHP)'];
    const rows    = d.items.map(item => [
        `"${item.ref}"`,
        `"${item.name}"`,
        `"${item.court}"`,
        item.amount,
    ]);
    const total   = ['', '', 'TOTAL', d.amount];

    const csvContent = [
        [`TDA Court – ${d.title}`],
        [`Period: ${d.label}`],
        [`Generated: ${new Date().toLocaleString()}`],
        [],
        headers,
        ...rows,
        [],
        total,
    ].map(r => r.join(',')).join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `tda-revenue-${currentPeriod}-${new Date().toISOString().slice(0,10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
}

// Close on backdrop click
document.getElementById('revenueModal').addEventListener('click', function(e) {
    if (e.target === this) closeRevenueModal();
});
</script>
</body>
</html>