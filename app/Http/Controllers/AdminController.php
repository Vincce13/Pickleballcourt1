<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Mail\BookingConfirmedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // ── DASHBOARD ──
    public function dashboard()
    {
        $stats = [
            'total'     => Reservation::count(),
            'pending'   => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'today'     => Reservation::whereDate('booking_date', today())->count(),
            'revenue'   => Reservation::where('payment_status', 'paid')->sum('amount'),
        ];

        $recent        = Reservation::latest()->take(5)->get();
        $todayBookings = Reservation::whereDate('booking_date', today())->orderBy('created_at')->get();

        return view('admin.dashboard', compact('stats', 'recent', 'todayBookings'));
    }

    // ── ALL RESERVATIONS ──
    public function reservations(Request $request)
    {
        $query = Reservation::latest();

        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('court'))   $query->where('court_id', $request->court);
        if ($request->filled('date'))    $query->whereDate('booking_date', $request->date);
        if ($request->filled('payment')) $query->where('payment_status', $request->payment);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('full_name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('reference_number', 'like', "%$s%")
                  ->orWhere('mobile_number', 'like', "%$s%");
            });
        }

        $reservations = $query->paginate(15)->withQueryString();
        return view('admin.reservations', compact('reservations'));
    }

    // ── SINGLE RESERVATION ──
    public function show(Reservation $reservation)
    {
        return view('admin.reservation-show', compact('reservation'));
    }

    // ── UPDATE STATUS ──
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $reservation->update(['status' => $request->status]);

        return back()->with('success', "Reservation {$reservation->reference_number} status updated to {$request->status}.");
    }

    /**
     * UPDATE PAYMENT STATUS
     * When admin marks as PAID → booking is auto-confirmed
     * → confirmation email sent to CUSTOMER
     */
    public function updatePayment(Request $request, Reservation $reservation)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,cancelled',
        ]);

        $wasPending = $reservation->payment_status !== 'paid';
        $nowPaid    = $request->payment_status === 'paid';

        $reservation->update([
            'payment_status' => $request->payment_status,
            'status'         => $nowPaid ? 'confirmed' : $reservation->status,
            'paid_at'        => $nowPaid ? now() : null,
        ]);

        // ── Send confirmation email to CUSTOMER when admin marks as paid ──
        if ($wasPending && $nowPaid) {
            try {
                Mail::to($reservation->email)->send(new BookingConfirmedMail($reservation));
                Log::info("Confirmation email sent to customer: {$reservation->email} for {$reservation->reference_number}");

                return back()->with('success',
                    "✅ Payment confirmed for {$reservation->reference_number}. " .
                    "Booking is now confirmed and a confirmation email has been sent to {$reservation->email}."
                );
            } catch (\Exception $e) {
                Log::error("Confirmation email failed: " . $e->getMessage());
                return back()->with('success',
                    "✅ Payment marked as paid for {$reservation->reference_number}, but confirmation email failed to send. " .
                    "Please notify the customer manually at {$reservation->email}."
                );
            }
        }

        return back()->with('success', "Payment status updated to {$request->payment_status}.");
    }

    // ── DELETE ──
    public function destroy(Reservation $reservation)
    {
        $ref = $reservation->reference_number;
        $reservation->delete();
        return redirect()->route('admin.reservations')
            ->with('success', "Reservation $ref has been deleted.");
    }
}