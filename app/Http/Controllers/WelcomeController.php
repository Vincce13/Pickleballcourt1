<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

class WelcomeController extends Controller
{
    private const ALL_SLOTS = [
        '6:00–7:00 AM','7:00–8:00 AM','8:00–9:00 AM','9:00–10:00 AM',
        '10:00–11:00 AM','11:00 AM–12:00 PM','12:00–1:00 PM','1:00–2:00 PM',
        '2:00–3:00 PM','3:00–4:00 PM','4:00–5:00 PM','5:00–6:00 PM',
        '6:00–7:00 PM','7:00–8:00 PM','8:00–9:00 PM','9:00–10:00 PM',
    ];

    public function index()
    {
        $today      = today()->toDateString();
        $totalSlots = count(self::ALL_SLOTS);

        $courts = [
            ['id' => 0, 'name' => 'Court A – Hardcourt', 'emoji' => '🏸', 'desc' => 'Indoor · Air-conditioned', 'price' => 300],
            ['id' => 1, 'name' => 'Court B – Clay',      'emoji' => '🎾', 'desc' => 'Outdoor · Morning slots',  'price' => 250],
            ['id' => 2, 'name' => 'Court C – Synthetic', 'emoji' => '🏐', 'desc' => 'Covered · All-weather',    'price' => 280],
        ];

        $totalAvailableToday = 0;

        foreach ($courts as &$court) {
            $booked = Reservation::bookedSlotsFor($court['id'], $today);
            $court['booked_count']    = count($booked);
            $court['available_count'] = $totalSlots - count($booked);
            $court['booked_slots']    = $booked;
            $totalAvailableToday     += $court['available_count'];
        }

        // Total bookings ever
        $totalBookings = Reservation::whereNotIn('status', ['cancelled'])->count();

        return view('welcome', compact('courts', 'totalAvailableToday', 'totalBookings'));
    }
}