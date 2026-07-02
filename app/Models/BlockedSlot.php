<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedSlot extends Model
{
    protected $fillable = [
        'court_id',
        'blocked_date',
        'time_slot',
        'reason',
        'blocked_by',
    ];

    protected $casts = [
        'blocked_date' => 'date',
    ];

    /**
     * Get blocked time slots for a given court + date.
     * Returns array: ['slot' => '6:00–7:00 AM', 'reason' => 'Event'] style map.
     */
    public static function blockedSlotsFor(int $courtId, string $date): array
    {
        return self::where('court_id', $courtId)
            ->whereDate('blocked_date', $date)
            ->get()
            ->mapWithKeys(fn($b) => [$b->time_slot => $b->reason ?: 'Blocked by admin'])
            ->toArray();
    }

    /**
     * Quick check if a specific slot is blocked.
     */
    public static function isBlocked(int $courtId, string $date, string $slot): bool
    {
        return self::where('court_id', $courtId)
            ->whereDate('blocked_date', $date)
            ->where('time_slot', $slot)
            ->exists();
    }
}