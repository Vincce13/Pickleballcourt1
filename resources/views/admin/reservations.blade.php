<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations – SZAM Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
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
        .main { margin-left:var(--sidebar); flex:1; display:flex; flex-direction:column; min-width:0; }
        .topbar { background:var(--white); border-bottom:1px solid var(--line); padding:0 32px; height:60px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; }
        .topbar-title { font-size:16px; font-weight:700; }
        .topbar-badge { background:var(--accent-light); color:var(--accent); font-size:12px; font-weight:600; padding:4px 12px; border-radius:20px; }
        .content { padding:28px 32px; }
        .flash { background:var(--green-light); border:1px solid #6ee7b7; border-radius:10px; padding:12px 16px; font-size:13px; color:#065f46; margin-bottom:20px; }
        /* FILTERS */
        .filters { background:var(--white); border-radius:14px; border:1px solid var(--line); padding:16px 20px; margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; }
        .filter-group { display:flex; flex-direction:column; gap:5px; }
        .filter-group label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px; }
        .filter-group input, .filter-group select { padding:8px 12px; border:1.5px solid var(--line); border-radius:8px; font-size:13px; font-family:'Inter',sans-serif; background:var(--surface); color:var(--ink); outline:none; min-width:140px; }
        .filter-group input:focus, .filter-group select:focus { border-color:var(--accent); }
        .btn-filter { padding:9px 20px; border-radius:8px; background:var(--accent); color:#fff; font-size:13px; font-weight:600; border:none; cursor:pointer; font-family:'Inter',sans-serif; }
        .btn-reset { padding:9px 16px; border-radius:8px; background:transparent; color:var(--muted); font-size:13px; font-weight:600; border:1.5px solid var(--line); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; }
        /* TABLE */
        .table-wrap { background:var(--white); border-radius:14px; border:1px solid var(--line); overflow:hidden; }
        .table-header { padding:16px 20px; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; align-items:center; }
        .table-header-title { font-size:14px; font-weight:700; }
        .table-count { font-size:12px; color:var(--muted); }
        table { width:100%; border-collapse:collapse; }
        thead th { background:var(--surface); padding:11px 16px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--muted); text-align:left; border-bottom:1px solid var(--line); white-space:nowrap; }
        tbody td { padding:13px 16px; font-size:13px; border-bottom:1px solid var(--line); vertical-align:middle; }
        tbody tr:last-child td { border-bottom:none; }
        tbody tr:hover td { background:var(--surface); }
        .badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
        .badge-pending   { background:var(--amber-light); color:var(--amber); }
        .badge-confirmed { background:var(--green-light); color:var(--green); }
        .badge-cancelled { background:var(--red-light); color:var(--red); }
        .badge-completed { background:var(--accent-light); color:var(--accent); }
        .badge-paid      { background:var(--green-light); color:var(--green); }
        .badge-unpaid    { background:var(--amber-light); color:var(--amber); }
        .view-link { color:var(--accent); font-size:12px; font-weight:600; text-decoration:none; padding:5px 12px; border-radius:6px; border:1px solid var(--accent-light); background:var(--accent-light); transition:all .15s; }
        .view-link:hover { background:var(--accent); color:#fff; }
        /* PAGINATION */
        .pagination { display:flex; gap:6px; justify-content:center; padding:20px; flex-wrap:wrap; }
        .pagination a, .pagination span { padding:7px 12px; border-radius:8px; font-size:13px; font-weight:500; text-decoration:none; border:1px solid var(--line); color:var(--muted); }
        .pagination a:hover { border-color:var(--accent); color:var(--accent); }
        .pagination .active-page { background:var(--accent); color:#fff; border-color:var(--accent); }
        /* EMPTY */
        .empty-state { text-align:center; padding:48px 24px; color:var(--muted); }
        .empty-icon { font-size:40px; margin-bottom:12px; }
        @media (max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .main { margin-left:0; }
            .content { padding:16px; }
            .topbar { padding:0 16px; }
            .filters { flex-direction:column; }
            .filter-group input, .filter-group select { min-width:unset; width:100%; }
            table { font-size:12px; }
            thead th, tbody td { padding:10px 10px; }
        }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo">SZAM<span>COURT</span></div>
    <div class="sidebar-section">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon">📊</span> Dashboard</a>
    <a href="{{ route('admin.reservations') }}" class="sidebar-link active"><span class="icon">📋</span> All Reservations</a>
    <div class="sidebar-section">Filter by<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations – TDA Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
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
        .main { margin-left:var(--sidebar); flex:1; display:flex; flex-direction:column; min-width:0; }
        .topbar { background:var(--white); border-bottom:1px solid var(--line); padding:0 32px; height:60px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; }
        .topbar-title { font-size:16px; font-weight:700; }
        .topbar-badge { background:var(--accent-light); color:var(--accent); font-size:12px; font-weight:600; padding:4px 12px; border-radius:20px; }
        .content { padding:28px 32px; }
        .flash { background:var(--green-light); border:1px solid #6ee7b7; border-radius:10px; padding:12px 16px; font-size:13px; color:#065f46; margin-bottom:20px; }
        /* FILTERS */
        .filters { background:var(--white); border-radius:14px; border:1px solid var(--line); padding:16px 20px; margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; }
        .filter-group { display:flex; flex-direction:column; gap:5px; }
        .filter-group label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px; }
        .filter-group input, .filter-group select { padding:8px 12px; border:1.5px solid var(--line); border-radius:8px; font-size:13px; font-family:'Inter',sans-serif; background:var(--surface); color:var(--ink); outline:none; min-width:140px; }
        .filter-group input:focus, .filter-group select:focus { border-color:var(--accent); }
        .btn-filter { padding:9px 20px; border-radius:8px; background:var(--accent); color:#fff; font-size:13px; font-weight:600; border:none; cursor:pointer; font-family:'Inter',sans-serif; }
        .btn-reset { padding:9px 16px; border-radius:8px; background:transparent; color:var(--muted); font-size:13px; font-weight:600; border:1.5px solid var(--line); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; }
        /* TABLE */
        .table-wrap { background:var(--white); border-radius:14px; border:1px solid var(--line); overflow:hidden; }
        .table-header { padding:16px 20px; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; align-items:center; }
        .table-header-title { font-size:14px; font-weight:700; }
        .table-count { font-size:12px; color:var(--muted); }
        table { width:100%; border-collapse:collapse; }
        thead th { background:var(--surface); padding:11px 16px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--muted); text-align:left; border-bottom:1px solid var(--line); white-space:nowrap; }
        tbody td { padding:13px 16px; font-size:13px; border-bottom:1px solid var(--line); vertical-align:middle; }
        tbody tr:last-child td { border-bottom:none; }
        tbody tr:hover td { background:var(--surface); }
        .badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
        .badge-pending   { background:var(--amber-light); color:var(--amber); }
        .badge-confirmed { background:var(--green-light); color:var(--green); }
        .badge-cancelled { background:var(--red-light); color:var(--red); }
        .badge-completed { background:var(--accent-light); color:var(--accent); }
        .badge-paid      { background:var(--green-light); color:var(--green); }
        .badge-unpaid    { background:var(--amber-light); color:var(--amber); }
        .view-link { color:var(--accent); font-size:12px; font-weight:600; text-decoration:none; padding:5px 12px; border-radius:6px; border:1px solid var(--accent-light); background:var(--accent-light); transition:all .15s; }
        .view-link:hover { background:var(--accent); color:#fff; }
        /* PAGINATION */
        .pagination { display:flex; gap:6px; justify-content:center; padding:20px; flex-wrap:wrap; }
        .pagination a, .pagination span { padding:7px 12px; border-radius:8px; font-size:13px; font-weight:500; text-decoration:none; border:1px solid var(--line); color:var(--muted); }
        .pagination a:hover { border-color:var(--accent); color:var(--accent); }
        .pagination .active-page { background:var(--accent); color:#fff; border-color:var(--accent); }
        /* EMPTY */
        .empty-state { text-align:center; padding:48px 24px; color:var(--muted); }
        .empty-icon { font-size:40px; margin-bottom:12px; }
        @media (max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .main { margin-left:0; }
            .content { padding:16px; }
            .topbar { padding:0 16px; }
            .filters { flex-direction:column; }
            .filter-group input, .filter-group select { min-width:unset; width:100%; }
            table { font-size:12px; }
            thead th, tbody td { padding:10px 10px; }
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
        <div class="topbar-title">All Reservations</div>
        <span class="topbar-badge">Admin Panel</span>
    </div>
    <div class="content">
        @if(session('success'))
        <div class="flash">✅ {{ session('success') }}</div>
        @endif

        <!-- FILTERS -->
        <form method="GET" action="{{ route('admin.reservations') }}">
            <div class="filters">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" placeholder="Name, email, reference…" value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All statuses</option>
                        <option value="pending"   {{ request('status')=='pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status')=='confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Court</label>
                    <select name="court">
                        <option value="">All courts</option>
                        <option value="0" {{ request('court')==='0' ? 'selected' : '' }}>Court A – Hardcourt</option>
                        <option value="1" {{ request('court')==='1' ? 'selected' : '' }}>Court B – Clay</option>
                        <option value="2" {{ request('court')==='2' ? 'selected' : '' }}>Court C – Synthetic</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Date</label>
                    <input type="date" name="date" value="{{ request('date') }}">
                </div>
                <button type="submit" class="btn-filter">Filter</button>
                <a href="{{ route('admin.reservations') }}" class="btn-reset">Reset</a>
            </div>
        </form>

        <!-- TABLE -->
        <div class="table-wrap">
            <div class="table-header">
                <div class="table-header-title">Reservations</div>
                <div class="table-count">{{ $reservations->total() }} total</div>
            </div>
            @if($reservations->count())
            <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Client</th>
                        <th>Court</th>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Receipt</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $r)
                    <tr>
                        <td style="font-family:monospace;font-weight:700;color:var(--accent);font-size:12px">{{ $r->reference_number }}</td>
                        <td>
                            <div style="font-weight:600">{{ $r->full_name }}</div>
                            <div style="font-size:11px;color:var(--muted)">{{ $r->mobile_number }}</div>
                        </td>
                        <td style="font-size:12px">{{ $r->court_name }}</td>
                        <td style="white-space:nowrap;font-size:12px">{{ $r->booking_date->format('M d, Y') }}</td>
                        <td style="white-space:nowrap;font-size:12px">{{ $r->time_slot }}</td>
                        <td style="font-weight:700;color:var(--green)">₱{{ number_format($r->amount) }}</td>
                        <td><span class="badge badge-{{ $r->payment_status === 'paid' ? 'paid' : 'unpaid' }}">{{ ucfirst($r->payment_status) }}</span></td>
                        <td><span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                        <td><a href="{{ route('admin.reservations.show', $r) }}" class="view-link">View →</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <!-- PAGINATION -->
            @if($reservations->hasPages())
            <div class="pagination">
                @if($reservations->onFirstPage())
                    <span>‹ Prev</span>
                @else
                    <a href="{{ $reservations->previousPageUrl() }}">‹ Prev</a>
                @endif
                @foreach($reservations->getUrlRange(1, $reservations->lastPage()) as $page => $url)
                    @if($page == $reservations->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
                @if($reservations->hasMorePages())
                    <a href="{{ $reservations->nextPageUrl() }}">Next ›</a>
                @else
                    <span>Next ›</span>
                @endif
            </div>
            @endif
            @else
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <div style="font-weight:600;margin-bottom:4px">No reservations found</div>
                <div style="font-size:13px">Try adjusting your filters</div>
            </div>
            @endif
        </div>
    </div>
</div>
</body>
</html> Status</div>
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
        <div class="topbar-title">All Reservations</div>
        <span class="topbar-badge">Admin Panel</span>
    </div>
    <div class="content">
        @if(session('success'))
        <div class="flash">✅ {{ session('success') }}</div>
        @endif

        <!-- FILTERS -->
        <form method="GET" action="{{ route('admin.reservations') }}">
            <div class="filters">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" placeholder="Name, email, reference…" value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All statuses</option>
                        <option value="pending"   {{ request('status')=='pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status')=='confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Court</label>
                    <select name="court">
                        <option value="">All courts</option>
                        <option value="0" {{ request('court')==='0' ? 'selected' : '' }}>Court A – Hardcourt</option>
                        <option value="1" {{ request('court')==='1' ? 'selected' : '' }}>Court B – Clay</option>
                        <option value="2" {{ request('court')==='2' ? 'selected' : '' }}>Court C – Synthetic</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Date</label>
                    <input type="date" name="date" value="{{ request('date') }}">
                </div>
                <button type="submit" class="btn-filter">Filter</button>
                <a href="{{ route('admin.reservations') }}" class="btn-reset">Reset</a>
            </div>
        </form>

        <!-- TABLE -->
        <div class="table-wrap">
            <div class="table-header">
                <div class="table-header-title">Reservations</div>
                <div class="table-count">{{ $reservations->total() }} total</div>
            </div>
            @if($reservations->count())
            <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Client</th>
                        <th>Court</th>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $r)
                    <tr>
                        <td style="font-family:monospace;font-weight:700;color:var(--accent);font-size:12px">{{ $r->reference_number }}</td>
                        <td>
                            <div style="font-weight:600">{{ $r->full_name }}</div>
                            <div style="font-size:11px;color:var(--muted)">{{ $r->mobile_number }}</div>
                        </td>
                        <td style="font-size:12px">{{ $r->court_name }}</td>
                        <td style="white-space:nowrap;font-size:12px">{{ $r->booking_date->format('M d, Y') }}</td>
                        <td style="white-space:nowrap;font-size:12px">{{ $r->time_slots_display }}</td>
                        <td style="font-weight:700;color:var(--green)">₱{{ number_format($r->amount) }}</td>
                        <td><span class="badge badge-{{ $r->payment_status === 'paid' ? 'paid' : 'unpaid' }}">{{ ucfirst($r->payment_status) }}</span></td>
                        <td><span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                        <td><a href="{{ route('admin.reservations.show', $r) }}" class="view-link">View →</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <!-- PAGINATION -->
            @if($reservations->hasPages())
            <div class="pagination">
                @if($reservations->onFirstPage())
                    <span>‹ Prev</span>
                @else
                    <a href="{{ $reservations->previousPageUrl() }}">‹ Prev</a>
                @endif
                @foreach($reservations->getUrlRange(1, $reservations->lastPage()) as $page => $url)
                    @if($page == $reservations->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
                @if($reservations->hasMorePages())
                    <a href="{{ $reservations->nextPageUrl() }}">Next ›</a>
                @else
                    <span>Next ›</span>
                @endif
            </div>
            @endif
            @else
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <div style="font-weight:600;margin-bottom:4px">No reservations found</div>
                <div style="font-size:13px">Try adjusting your filters</div>
            </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>