<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blocked Slots – TDA Admin</title>
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

        .btn-primary { padding:10px 20px; border-radius:8px; background:var(--accent); color:#fff; font-size:13px; font-weight:600; border:none; cursor:pointer; font-family:'Inter',sans-serif; display:inline-flex; align-items:center; gap:6px; }
        .btn-primary:hover { background:var(--accent-dark); }

        .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .page-header h1 { font-size:18px; font-weight:700; }
        .page-header p { font-size:13px; color:var(--muted); margin-top:2px; }

        /* FILTERS */
        .filters { background:var(--white); border-radius:14px; border:1px solid var(--line); padding:16px 20px; margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; }
        .filter-group { display:flex; flex-direction:column; gap:5px; }
        .filter-group label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px; }
        .filter-group input, .filter-group select { padding:8px 12px; border:1.5px solid var(--line); border-radius:8px; font-size:13px; font-family:'Inter',sans-serif; background:var(--surface); color:var(--ink); outline:none; min-width:140px; }
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
        .badge-court { background:var(--accent-light); color:var(--accent); font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; }
        .reason-tag { font-size:12px; color:var(--ink); }
        .reason-empty { font-size:12px; color:var(--muted); font-style:italic; }
        .btn-delete { color:var(--red); font-size:12px; font-weight:600; text-decoration:none; padding:5px 12px; border-radius:6px; border:1px solid var(--red-light); background:var(--red-light); cursor:pointer; font-family:'Inter',sans-serif; }
        .btn-delete:hover { background:var(--red); color:#fff; }
        .empty-state { text-align:center; padding:48px 24px; color:var(--muted); }
        .empty-icon { font-size:40px; margin-bottom:12px; }
        .pagination { display:flex; gap:6px; justify-content:center; padding:20px; flex-wrap:wrap; }
        .pagination a, .pagination span { padding:7px 12px; border-radius:8px; font-size:13px; font-weight:500; text-decoration:none; border:1px solid var(--line); color:var(--muted); }
        .pagination .active-page { background:var(--accent); color:#fff; border-color:var(--accent); }

        /* MODAL */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999; align-items:center; justify-content:center; }
        .modal-overlay.show { display:flex; }
        .modal-box { background:var(--white); border-radius:16px; border:1px solid var(--line); width:92%; max-width:520px; max-height:88vh; overflow-y:auto; box-shadow:0 24px 60px rgba(0,0,0,.18); }
        .modal-header { padding:20px 24px; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; align-items:center; }
        .modal-title { font-family:'Syne',sans-serif; font-weight:800; font-size:17px; }
        .modal-close { width:30px; height:30px; border-radius:8px; border:1px solid var(--line); background:var(--surface); cursor:pointer; font-size:16px; color:var(--muted); }
        .modal-body { padding:22px 24px; }
        .field { margin-bottom:18px; }
        .field label { display:block; font-size:13px; font-weight:600; margin-bottom:8px; }
        .field input[type=date] { width:100%; padding:10px 12px; border:1.5px solid var(--line); border-radius:8px; font-size:14px; font-family:'Inter',sans-serif; background:var(--surface); }
        .field textarea { width:100%; padding:10px 12px; border:1.5px solid var(--line); border-radius:8px; font-size:13px; font-family:'Inter',sans-serif; background:var(--surface); resize:vertical; min-height:60px; }
        .court-checks { display:flex; gap:10px; flex-wrap:wrap; }
        .check-pill { display:flex; align-items:center; gap:6px; padding:9px 14px; border-radius:9px; border:1.5px solid var(--line); background:var(--surface); cursor:pointer; font-size:13px; font-weight:500; transition:all .15s; }
        .check-pill input { display:none; }
        .check-pill.checked { border-color:var(--accent); background:var(--accent-light); color:var(--accent); font-weight:700; }
        .slots-pick-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; max-height:220px; overflow-y:auto; padding:4px; }
        .slot-pick { padding:8px 4px; border-radius:8px; border:1.5px solid var(--line); background:var(--surface); text-align:center; font-size:11px; font-weight:500; cursor:pointer; transition:all .15s; user-select:none; }
        .slot-pick.checked { border-color:var(--accent); background:var(--accent); color:#fff; font-weight:700; }
        .slots-loading-msg { font-size:12px; color:var(--muted); padding:10px; text-align:center; }
        .modal-footer { padding:18px 24px; border-top:1px solid var(--line); display:flex; justify-content:flex-end; gap:10px; }
        .btn-cancel { padding:10px 18px; border-radius:8px; border:1.5px solid var(--line); background:transparent; color:var(--muted); font-size:13px; font-weight:600; cursor:pointer; font-family:'Inter',sans-serif; }

        @media (max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .main { margin-left:0; }
            .content { padding:16px; }
            .topbar { padding:0 16px; }
            .filters { flex-direction:column; }
            .filter-group input, .filter-group select { min-width:unset; width:100%; }
            table { font-size:12px; }
            thead th, tbody td { padding:10px 10px; }
            .slots-pick-grid { grid-template-columns:repeat(2,1fr); }
        }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo">TDA<span>COURT</span></div>
    <div class="sidebar-section">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon">📊</span> Dashboard</a>
    <a href="{{ route('admin.reservations') }}" class="sidebar-link"><span class="icon">📋</span>  Reservations</a>
    <a href="{{ route('admin.blocked-slots') }}" class="sidebar-link active"><span class="icon">🚧</span> Blocked Slots</a>
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
        <div class="topbar-title">Blocked Slots</div>
        <span class="topbar-badge">Admin Panel</span>
    </div>
    <div class="content">
        @if(session('success'))
        <div class="flash">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="flash" style="background:var(--red-light);border-color:#fca5a5;color:#991b1b;">
            {{ $errors->first() }}
        </div>
        @endif

        <div class="page-header">
            <div>
                <h1>🚧 Blocked Time Slots</h1>
                <p>Block specific time slots for events, maintenance, or other reasons — customers won't be able to book these.</p>
            </div>
            <button class="btn-primary" onclick="openBlockModal()">+ Block New Slot</button>
        </div>

        <!-- FILTERS -->
        <form method="GET" action="{{ route('admin.blocked-slots') }}" id="filterForm">
            <div class="filters">
                <div class="filter-group">
                    <label>Court</label>
                    <select name="court" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All courts</option>
                        @foreach($courts as $id => $name)
                        <option value="{{ $id }}" {{ request('court')==(string)$id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" onchange="document.getElementById('filterForm').submit()">
                </div>
                <a href="{{ route('admin.blocked-slots') }}" class="btn-reset">Reset</a>
            </div>
        </form>

        <!-- TABLE -->
        <div class="table-wrap">
            <div class="table-header">
                <div class="table-header-title">Blocked Slots</div>
                <div class="table-count">{{ $blocks->total() }} total</div>
            </div>
            @if($blocks->count())
            <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>Court</th>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Reason</th>
                        <th>Blocked By</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blocks as $b)
                    <tr>
                        <td><span class="badge-court">{{ $courts[$b->court_id] ?? 'Court '.$b->court_id }}</span></td>
                        <td style="white-space:nowrap">{{ $b->blocked_date->format('M d, Y') }}</td>
                        <td style="white-space:nowrap;font-weight:600">{{ $b->time_slot }}</td>
                        <td>
                            @if($b->reason)
                                <span class="reason-tag">🚧 {{ $b->reason }}</span>
                            @else
                                <span class="reason-empty">No reason given</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--muted)">{{ $b->blocked_by ?? '—' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.blocked-slots.destroy', $b) }}" onsubmit="return confirm('Unblock this slot? It will become available for booking again.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">Unblock</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @if($blocks->hasPages())
            <div class="pagination">
                @if($blocks->onFirstPage()) <span>‹ Prev</span> @else <a href="{{ $blocks->previousPageUrl() }}">‹ Prev</a> @endif
                @foreach($blocks->getUrlRange(1, $blocks->lastPage()) as $page => $url)
                    @if($page == $blocks->currentPage()) <span class="active-page">{{ $page }}</span>
                    @else <a href="{{ $url }}">{{ $page }}</a> @endif
                @endforeach
                @if($blocks->hasMorePages()) <a href="{{ $blocks->nextPageUrl() }}">Next ›</a> @else <span>Next ›</span> @endif
            </div>
            @endif
            @else
            <div class="empty-state">
                <div class="empty-icon">🚧</div>
                <div style="font-weight:600;margin-bottom:4px">No blocked slots</div>
                <div style="font-size:13px">All time slots are currently open for booking.</div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ── BLOCK SLOT MODAL ── -->
<div class="modal-overlay" id="blockModal">
    <div class="modal-box">
        <form method="POST" action="{{ route('admin.blocked-slots.store') }}">
            @csrf
            <div class="modal-header">
                <div class="modal-title">🚧 Block Time Slot(s)</div>
                <button type="button" class="modal-close" onclick="closeBlockModal()">✕</button>
            </div>
            <div class="modal-body">

                <div class="field">
                    <label>Select Court(s) <span style="color:var(--red)">*</span></label>
                    <div class="court-checks" id="courtChecks">
                        @foreach($courts as $id => $name)
                        <label class="check-pill" data-id="{{ $id }}">
                            <input type="checkbox" name="court_ids[]" value="{{ $id }}" onchange="toggleCourtPill(this)">
                            {{ $name }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="field">
                    <label>Date <span style="color:var(--red)">*</span></label>
                    <input type="date" name="date" id="blockDate" required min="{{ now()->format('Y-m-d') }}" onchange="loadSlotsForBlock()">
                </div>

                <div class="field">
                    <label>Time Slot(s) <span style="color:var(--red)">*</span></label>
                    <div id="slotsPickWrap">
                        <div class="slots-loading-msg">Select a court and date first to load available slots.</div>
                    </div>
                </div>

                <div class="field" style="margin-bottom:0">
                    <label>Reason (optional)</label>
                    <textarea name="reason" placeholder="e.g. Tournament Event, Court Maintenance, Private Booking…"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeBlockModal()">Cancel</button>
                <button type="submit" class="btn-primary">🚧 Block Selected Slot(s)</button>
            </div>
        </form>
    </div>
</div>

<script>
const ALL_SLOTS = [
    '6:00–7:00 AM','7:00–8:00 AM','8:00–9:00 AM','9:00–10:00 AM','10:00–11:00 AM','11:00 AM–12:00 PM',
    '12:00–1:00 PM','1:00–2:00 PM','2:00–3:00 PM','3:00–4:00 PM','4:00–5:00 PM','5:00–6:00 PM',
    '6:00–7:00 PM','7:00–8:00 PM','8:00–9:00 PM','9:00–10:00 PM'
];

function openBlockModal() {
    document.getElementById('blockModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeBlockModal() {
    document.getElementById('blockModal').classList.remove('show');
    document.body.style.overflow = '';
}
document.getElementById('blockModal').addEventListener('click', function(e){ if (e.target === this) closeBlockModal(); });

function toggleCourtPill(checkbox) {
    checkbox.closest('.check-pill').classList.toggle('checked', checkbox.checked);
    loadSlotsForBlock();
}

async function loadSlotsForBlock() {
    const checkedCourts = Array.from(document.querySelectorAll('#courtChecks input:checked')).map(c => c.value);
    const date = document.getElementById('blockDate').value;
    const wrap = document.getElementById('slotsPickWrap');

    if (checkedCourts.length === 0 || !date) {
        wrap.innerHTML = '<div class="slots-loading-msg">Select a court and date first to load available slots.</div>';
        return;
    }

    wrap.innerHTML = '<div class="slots-loading-msg">⏳ Checking availability…</div>';

    // Fetch availability for the FIRST selected court to determine which slots are already booked/blocked
    // (If multiple courts selected, we still let admin pick any slot — booked-check happens server-side per court on submit)
    try {
        const res = await fetch(`/api/slots?court_id=${checkedCourts[0]}&date=${date}`);
        const data = await res.json();
        const taken = new Set([...(data.booked_slots||[]), ...Object.keys(data.blocked_slots||{})]);

        const grid = document.createElement('div');
        grid.className = 'slots-pick-grid';
        ALL_SLOTS.forEach(slot => {
            const isTaken = taken.has(slot);
            const pick = document.createElement('div');
            pick.className = 'slot-pick' + (isTaken ? '' : '');
            pick.textContent = slot;
            if (isTaken) {
                pick.style.opacity = '0.4';
                pick.style.cursor = 'not-allowed';
                pick.title = 'Already booked or blocked';
            } else {
                pick.onclick = () => toggleSlotPick(pick, slot);
            }
            grid.appendChild(pick);
        });
        wrap.innerHTML = '';
        wrap.appendChild(grid);
    } catch (e) {
        wrap.innerHTML = '<div class="slots-loading-msg">⚠️ Failed to load slots.</div>';
    }
}

const selectedBlockSlots = new Set();

function toggleSlotPick(el, slot) {
    el.classList.toggle('checked');
    if (selectedBlockSlots.has(slot)) {
        selectedBlockSlots.delete(slot);
    } else {
        selectedBlockSlots.add(slot);
    }
    syncHiddenSlotInputs();
}

function syncHiddenSlotInputs() {
    document.querySelectorAll('input[name="time_slots[]"]').forEach(el => el.remove());
    const form = document.querySelector('#blockModal form');
    selectedBlockSlots.forEach(slot => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'time_slots[]';
        input.value = slot;
        form.appendChild(input);
    });
}

@if($errors->any())
    openBlockModal();
@endif
</script>

</body>
</html>