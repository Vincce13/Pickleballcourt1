<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Mail\BookingConfirmedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // ── API: Revenue for a specific month (used by dashboard month navigator) ──
    public function revenueForMonth(Request $request)
    {
        $request->validate([
            'year'  => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year  = (int) $request->year;
        $month = (int) $request->month;

        $items = Reservation::where('payment_status', 'paid')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($i) => [
                'ref'    => $i->reference_number,
                'name'   => $i->full_name,
                'court'  => $i->court_name,
                'amount' => $i->amount,
            ]);

        $monthLabel = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y');

        return response()->json([
            'amount' => $items->sum('amount'),
            'count'  => $items->count(),
            'label'  => $monthLabel,
            'title'  => $monthLabel . ' Paid Bookings',
            'items'  => $items,
        ]);
    }

    // ── DASHBOARD ──
    public function dashboard()
    {
        $paidBase = Reservation::where('payment_status', 'paid');

        $stats = [
            // ── EXISTING ──
            'total'     => Reservation::count(),
            'pending'   => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'today'     => Reservation::whereDate('booking_date', today())->count(),
            'revenue'   => (clone $paidBase)->sum('amount'),

            // ── TODAY ──
            'revenue_today'       => (clone $paidBase)->whereDate('created_at', today())->sum('amount'),
            'revenue_today_count' => (clone $paidBase)->whereDate('created_at', today())->count(),
            'revenue_today_items' => (clone $paidBase)->whereDate('created_at', today())
                                        ->orderByDesc('created_at')->get()
                                        ->map(fn($i) => ['ref' => $i->reference_number, 'name' => $i->full_name, 'court' => $i->court_name, 'amount' => $i->amount]),

            // ── THIS WEEK ──
            'revenue_week'        => (clone $paidBase)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount'),
            'revenue_week_count'  => (clone $paidBase)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'revenue_week_items'  => (clone $paidBase)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                                        ->orderByDesc('created_at')->get()
                                        ->map(fn($i) => ['ref' => $i->reference_number, 'name' => $i->full_name, 'court' => $i->court_name, 'amount' => $i->amount]),

            // ── THIS MONTH ──
            'revenue_month'       => (clone $paidBase)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount'),
            'revenue_month_count' => (clone $paidBase)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'revenue_month_items' => (clone $paidBase)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)
                                        ->orderByDesc('created_at')->get()
                                        ->map(fn($i) => ['ref' => $i->reference_number, 'name' => $i->full_name, 'court' => $i->court_name, 'amount' => $i->amount]),

            // ── ALL TIME ──
            'revenue_all_count'   => (clone $paidBase)->count(),
            'revenue_all_items'   => (clone $paidBase)->orderByDesc('created_at')->get()
                                        ->map(fn($i) => ['ref' => $i->reference_number, 'name' => $i->full_name, 'court' => $i->court_name, 'amount' => $i->amount]),
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