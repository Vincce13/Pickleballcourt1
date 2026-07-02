<?php

namespace App\Http\Controllers;

use App\Models\BlockedSlot;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlockedSlotController extends Controller
{
    private const COURTS = [
        0 => 'Court A – Hardcourt',
        1 => 'Court B – Clay',
        2 => 'Court C – Synthetic',
    ];

    // GET /admin/blocked-slots
    public function index(Request $request)
    {
        // Auto-delete blocks from previous days
        BlockedSlot::where('blocked_date', '<', today())->delete();

        $query = BlockedSlot::where('blocked_date', '>=', today())
                    ->orderByDesc('blocked_date')->orderBy('time_slot');

        if ($request->filled('date')) {
            $query->whereDate('blocked_date', $request->date);
        }
        if ($request->filled('court')) {
            $query->where('court_id', $request->court);
        }

        $blocks = $query->paginate(20)->withQueryString();

        return view('admin.blocked-slots', [
            'blocks' => $blocks,
            'courts' => self::COURTS,
        ]);
    }

    // POST /admin/blocked-slots
    // Admin selects court(s) + date + slot(s) + reason
    public function store(Request $request)
    {
        $request->validate([
            'court_ids'   => 'required|array|min:1',
            'court_ids.*' => 'integer|between:0,2',
            'date'        => 'required|date|after_or_equal:today',
            'time_slots'  => 'required|array|min:1',
            'time_slots.*'=> 'string',
            'reason'      => 'nullable|string|max:255',
        ]);

        $created = 0;
        $skipped = [];

        foreach ($request->court_ids as $courtId) {
            foreach ($request->time_slots as $slot) {

                // Don't block a slot that's already booked
                $alreadyBooked = Reservation::where('court_id', $courtId)
                    ->whereDate('booking_date', $request->date)
                    ->whereJsonContains('time_slots', $slot)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->exists();

                if ($alreadyBooked) {
                    $skipped[] = self::COURTS[$courtId] . ' – ' . $slot;
                    continue;
                }

                BlockedSlot::firstOrCreate(
                    [
                        'court_id'     => $courtId,
                        'blocked_date' => $request->date,
                        'time_slot'    => $slot,
                    ],
                    [
                        'reason'      => $request->reason,
                        'blocked_by'  => auth('admin')->user()->name ?? auth('admin')->user()->email ?? 'Admin',
                    ]
                );
                $created++;
            }
        }

        $message = "✅ {$created} slot(s) blocked successfully.";
        if (!empty($skipped)) {
            $message .= ' ⚠️ Skipped (already booked): ' . implode(', ', $skipped);
        }

        return back()->with('success', $message);
    }

    // DELETE /admin/blocked-slots/{blockedSlot}
    public function destroy(BlockedSlot $blockedSlot)
    {
        $blockedSlot->delete();
        return back()->with('success', 'Block removed. The slot is now available for booking again.');
    }

    // POST /admin/blocked-slots/bulk-destroy
    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        BlockedSlot::whereIn('id', $request->ids)->delete();
        return back()->with('success', count($request->ids) . ' block(s) removed.');
    }
}