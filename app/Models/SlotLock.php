<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SlotLock extends Model
{
    protected $fillable = [
        'court_id',
        'booking_date',
        'time_slot',
        'session_id',
        'expires_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'expires_at'   => 'datetime',
        'court_id'     => 'integer',
    ];

    const LOCK_MINUTES = 3; // 3-minute lock duration

    // ── HELPERS ──

    /**
     * Clean up all expired locks.
     */
    public static function clearExpired(): void
    {
        static::where('expires_at', '<', now())->delete();
    }

    /**
     * Check if a slot is locked by someone else.
     */
    public static function isLocked(int $courtId, string $date, string $slot, string $mySessionId): bool
    {
        return static::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->where('time_slot', $slot)
            ->where('session_id', '!=', $mySessionId)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Get all locked slots for a court+date (excluding my own locks).
     * Returns array of slot strings.
     */
    public static function lockedSlotsFor(int $courtId, string $date, string $mySessionId = ''): array
    {
        return static::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->where('expires_at', '>', now())
            ->where('session_id', '!=', $mySessionId)
            ->pluck('time_slot')
            ->toArray();
    }

    /**
     * Lock a slot for a session. Returns false if already locked by someone else.
     */
    public static function lockSlot(int $courtId, string $date, string $slot, string $sessionId): bool
    {
        // Clean expired locks first
        static::clearExpired();

        // Check if someone else holds this lock
        if (static::isLocked($courtId, $date, $slot, $sessionId)) {
            return false;
        }

        // Upsert — create or refresh our own lock
        static::updateOrCreate(
            [
                'court_id'     => $courtId,
                'booking_date' => $date,
                'time_slot'    => $slot,
            ],
            [
                'session_id' => $sessionId,
                'expires_at' => now()->addMinutes(static::LOCK_MINUTES),
            ]
        );

        return true;
    }

    /**
     * Unlock a specific slot for a session.
     */
    public static function unlockSlot(int $courtId, string $date, string $slot, string $sessionId): void
    {
        static::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->where('time_slot', $slot)
            ->where('session_id', $sessionId)
            ->delete();
    }

    /**
     * Release ALL locks held by a session.
     */
    public static function releaseSession(string $sessionId): void
    {
        static::where('session_id', $sessionId)->delete();
    }

    /**
     * Refresh/extend a lock's expiry (called when user is still active).
     */
    public static function refreshLock(int $courtId, string $date, string $slot, string $sessionId): bool
    {
        $updated = static::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->where('time_slot', $slot)
            ->where('session_id', $sessionId)
            ->where('expires_at', '>', now())
            ->update(['expires_at' => now()->addMinutes(static::LOCK_MINUTES)]);

        return $updated > 0;
    }
}