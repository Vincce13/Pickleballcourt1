<?php

namespace App\Http\Controllers;

use App\Models\SlotLock;
use App\Models\Reservation;
use App\Models\BlockedSlot;
use Illuminate\Http\Request;

class SlotLockController extends Controller
{
    /**
     * POST /api/slots/lock
     * Lock a slot for the current session (called when user clicks a slot).
     */
    public function lock(Request $request)
    {
        $request->validate([
            'court_id'     => 'required|integer|between:0,2',
            'date'         => 'required|date|after_or_equal:today',
            'time_slot'    => 'required|string',
        ]);

        $sessionId = $request->session()->getId();
        $courtId   = (int) $request->court_id;
        $date      = $request->date;
        $slot      = $request->time_slot;

        // Check if slot is blocked by admin first
        if (BlockedSlot::isBlocked($courtId, $date, $slot)) {
            return response()->json([
                'success' => false,
                'reason'  => 'blocked',
                'message' => 'This slot is currently unavailable.',
            ], 409);
        }

        // Check if slot is already permanently booked
        $booked = Reservation::bookedSlotsFor($courtId, $date);
        if (in_array($slot, $booked)) {
            return response()->json([
                'success' => false,
                'reason'  => 'booked',
                'message' => 'This slot is already booked.',
            ], 409);
        }

        // Try to lock
        $locked = SlotLock::lockSlot($courtId, $date, $slot, $sessionId);

        if (!$locked) {
            return response()->json([
                'success'  => false,
                'reason'   => 'locked',
                'message'  => 'This slot is currently being reserved by another user. Please choose a different slot.',
                'expires_in' => $this->getLockExpiry($courtId, $date, $slot),
            ], 409);
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Slot locked for 3 minutes.',
            'expires_at' => now()->addMinutes(SlotLock::LOCK_MINUTES)->toIso8601String(),
            'expires_in' => SlotLock::LOCK_MINUTES * 60, // seconds
        ]);
    }

    /**
     * POST /api/slots/unlock
     * Unlock a slot (called when user deselects a slot).
     */
    public function unlock(Request $request)
    {
        $request->validate([
            'court_id'  => 'required|integer|between:0,2',
            'date'      => 'required|date',
            'time_slot' => 'required|string',
        ]);

        $sessionId = $request->session()->getId();

        SlotLock::unlockSlot(
            (int) $request->court_id,
            $request->date,
            $request->time_slot,
            $sessionId
        );

        return response()->json(['success' => true]);
    }

    /**
     * POST /api/slots/unlock-all
     * Release all locks for this session (called on page unload or back button).
     */
    public function unlockAll(Request $request)
    {
        SlotLock::releaseSession($request->session()->getId());
        return response()->json(['success' => true]);
    }

    /**
     * GET /api/slots?court_id=0&date=2026-06-12
     * Returns booked + locked + blocked slots for a court+date.
     */
    public function availability(Request $request)
    {
        $request->validate([
            'court_id' => 'required|integer|between:0,2',
            'date'     => 'required|date',
        ]);

        $courtId   = (int) $request->court_id;
        $date      = $request->date;
        $sessionId = $request->session()->getId();

        // Clean up expired locks
        SlotLock::clearExpired();

        $booked  = Reservation::bookedSlotsFor($courtId, $date);
        $locked  = SlotLock::lockedSlotsFor($courtId, $date, $sessionId);
        $blocked = BlockedSlot::blockedSlotsFor($courtId, $date); // ['slot' => 'reason']

        return response()->json([
            'booked_slots'  => array_values($booked),
            'locked_slots'  => array_values($locked), // slots locked by others
            'blocked_slots' => $blocked,               // {"6:00–7:00 AM": "Tournament Event"}
        ]);
    }

    private function getLockExpiry(int $courtId, string $date, string $slot): ?int
    {
        $lock = SlotLock::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->where('time_slot', $slot)
            ->where('expires_at', '>', now())
            ->first();

        return $lock ? (int) now()->diffInSeconds($lock->expires_at) : null;
    }
}