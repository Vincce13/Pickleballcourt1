<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Requests\StoreReservationRequest;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    private const COURTS = [
        0 => ['name' => 'Court A – Hardcourt', 'price' => 300],
        1 => ['name' => 'Court B – Clay',      'price' => 250],
        2 => ['name' => 'Court C – Synthetic', 'price' => 280],
    ];

    // GET /book
    public function create(Request $request)
    {
        $courtId = $request->query('court');
        return view('book', compact('courtId'));
    }

    // POST /book — just save, NO email sent yet
    public function store(StoreReservationRequest $request)
    {
        $data    = $request->validated();
        $courtId = (int) $data['court_id'];
        $court   = self::COURTS[$courtId];

        $slots = json_decode($data['time_slots'], true);

        if (empty($slots) || !is_array($slots)) {
            return back()->withInput()->withErrors([
                'time_slots' => 'Please select at least one time slot.',
            ]);
        }

        // Conflict check
        if (Reservation::hasConflict($courtId, $data['booking_date'], $slots)) {
            $booked    = Reservation::bookedSlotsFor($courtId, $data['booking_date']);
            $conflicts = array_intersect($slots, $booked);
            return back()->withInput()->withErrors([
                'time_slots' => 'Some slots are already taken: ' . implode(', ', $conflicts),
            ]);
        }

        $totalAmount = count($slots) * $court['price'];

        // Save — no email here
        $reservation = Reservation::create([
            'full_name'        => $data['full_name'],
            'mobile_number'    => $data['mobile_number'],
            'email'            => $data['email'],
            'court_id'         => $courtId,
            'court_name'       => $court['name'],
            'booking_date'     => $data['booking_date'],
            'time_slots'       => $slots,
            'amount'           => $totalAmount,
            'payment_method'   => $data['payment_method'],
            'payment_status'   => 'pending',
            'reference_number' => Reservation::generateReference(),
            'status'           => 'pending',
        ]);

        return redirect()->route('reservations.confirmation', $reservation->reference_number);
    }

    // GET /booking/confirmation/{reference}
    public function confirmation(string $reference)
    {
        $reservation = Reservation::where('reference_number', $reference)->firstOrFail();
        return view('booking-confirmation', compact('reservation'));
    }

    // GET /booking/{reference}
    public function show(string $reference)
    {
        $reservation = Reservation::where('reference_number', $reference)->firstOrFail();
        return view('booking-status', compact('reservation'));
    }

    // GET /api/slots
    public function bookedSlots(Request $request)
    {
        $request->validate([
            'court_id' => 'required|integer|between:0,2',
            'date'     => 'required|date',
        ]);

        $booked = Reservation::bookedSlotsFor(
            (int) $request->court_id,
            $request->date
        );

        return response()->json(['booked_slots' => array_values($booked)]);
    }
}