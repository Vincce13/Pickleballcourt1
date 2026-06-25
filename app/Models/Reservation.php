<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'mobile_number', 'email',
        'court_id', 'court_name', 'booking_date',
        'time_slots', 'amount',
        'payment_method', 'payment_status',
        'paymongo_payment_intent_id', 'paymongo_source_id',
        'payment_url', 'paid_at',
        'receipt_path', 'receipt_uploaded_at',   // ← new
        'reference_number', 'status', 'notes',
    ];

    protected $casts = [
        'booking_date'        => 'date',
        'amount'              => 'integer',
        'court_id'            => 'integer',
        'time_slots'          => 'array',
        'paid_at'             => 'datetime',
        'receipt_uploaded_at' => 'datetime',
    ];

    // ── SCOPES ──
    public function scopePending($query)   { return $query->where('status', 'pending'); }
    public function scopeConfirmed($query) { return $query->where('status', 'confirmed'); }
    public function scopeToday($query)     { return $query->whereDate('booking_date', today()); }

    // ── HELPERS ──
    public static function bookedSlotsFor(int $courtId, string $date): array
    {
        $reservations = static::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->get(['time_slots']);

        $booked = [];
        foreach ($reservations as $r) {
            if (is_array($r->time_slots)) {
                $booked = array_merge($booked, $r->time_slots);
            }
        }
        return array_unique($booked);
    }

    public static function hasConflict(int $courtId, string $date, array $slots): bool
    {
        $booked = static::bookedSlotsFor($courtId, $date);
        return count(array_intersect($slots, $booked)) > 0;
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'WPX-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (static::where('reference_number', $ref)->exists());
        return $ref;
    }

    // ── ACCESSORS ──
    public function getFormattedDateAttribute(): string
    {
        return $this->booking_date->format('D, F j, Y');
    }

    public function getTimeSlotsDisplayAttribute(): string
    {
        return is_array($this->time_slots) ? implode(', ', $this->time_slots) : '—';
    }

    public function getSlotCountAttribute(): int
    {
        return is_array($this->time_slots) ? count($this->time_slots) : 0;
    }

    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt_path
            ? asset('storage/' . $this->receipt_path)
            : null;
    }

    public function getHasReceiptAttribute(): bool
    {
        return !empty($this->receipt_path);
    }
}