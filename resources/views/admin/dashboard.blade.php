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
        .sidebar {
            width: var(--sidebar); background: var(--ink2); color: #fff;
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; height: 100vh; z-index: 100;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 24px 20px 20px;
            font-family: 'Syne', sans-serif; font-weight: 800;
            font-size: 18px; letter-spacing: 3px; color: #fff;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-logo span { color: #818cf8; }
        .sidebar-section { padding: 16px 12px 8px; font-size: 10px; font-weight: 700; letter-spacing: 2px; color: rgba(255,255,255,.35); text-transform: uppercase; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px; margin: 2px 8px;
            font-size: 14px; font-weight: 500; color: rgba(255,255,255,.7);
            text-decoration: none; transition: all .15s;
        }
        .sidebar-link:hover { background: rgba(255,255,255,.08); color: #fff; }
        .sidebar-link.active { background: var(--accent); color: #fff; }
        .sidebar-link .icon { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-footer { margin-top: auto; padding: 16px 12px; border-top: 1px solid rgba(255,255,255,.1); }
        .sidebar-footer a { display: flex; align-items: center; gap: 8px; font-size: 13px; color: rgba(255,255,255,.5); text-decoration: none; padding: 8px; border-radius: 6px; transition: all .15s; }
        .sidebar-footer a:hover { color: #fff; background: rgba(255,255,255,.08); }

        /* MAIN */
        .main { margin-left: var(--sidebar); flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* TOP BAR */
        .topbar {
            background: var(--white); border-bottom: 1px solid var(--line);
            padding: 0 32px; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 16px; font-weight: 700; color: var(--ink); }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-badge { background: var(--accent-light); color: var(--accent); font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; }

        /* CONTENT */
        .content { padding: 28px 32px; flex: 1; }

        /* FLASH */
        .flash { background: var(--green-light); border: 1px solid #6ee7b7; border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #065f46; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }

        /* STATS GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card { background: var(--white); border-radius: 14px; border: 1px solid var(--line); padding: 20px; }
        .stat-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
        .stat-card-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .stat-card-label { font-size: 12px; color: var(--muted); font-weight: 500; margin-bottom: 4px; }
        .stat-card-value { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: var(--ink); line-height: 1; }
        .stat-card-sub { font-size: 12px; color: var(--muted); margin-top: 6px; }

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

        /* TWO-COL GRID */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        /* TODAY LIST */
        .today-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid var(--line); font-size: 13px; }
        .today-item:last-child { border-bottom: none; }
        .today-time { font-weight: 700; color: var(--ink); min-width: 120px; }
        .today-name { color: var(--muted); }
        .today-court { font-size: 11px; color: var(--accent); font-weight: 600; }

        /* VIEW LINK */
        .view-link { color: var(--accent); font-size: 12px; font-weight: 600; text-decoration: none; }
        .view-link:hover { text-decoration: underline; }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .two-col { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .content { padding: 20px 16px; }
            .topbar { padding: 0 16px; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">TDA<span></span></div>
    <div class="sidebar-section">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link active"><span class="icon">📊</span> Dashboard</a>
    <a href="{{ route('admin.reservations') }}" class="sidebar-link"><span class="icon">📋</span> Reservations</a>
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

<!-- MAIN -->
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
            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-card-label">Revenue (Paid)</div>
                        <div class="stat-card-value" style="color:var(--green)">₱{{ number_format($stats['revenue']) }}</div>
                    </div>
                    <div class="stat-card-icon" style="background:var(--green-light)">💰</div>
                </div>
                <div class="stat-card-sub">From paid bookings</div>
            </div>
        </div>

        <!-- RECENT + TODAY -->
        <div class="two-col">

            <!-- RECENT RESERVATIONS -->
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
                                <td>
                                    <span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.reservations.show', $r) }}" class="view-link">View →</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:24px">No reservations yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TODAY'S SCHEDULE -->
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
</body>
</html>