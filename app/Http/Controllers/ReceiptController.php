<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Mail\NewReservationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    /**
     * POST /booking/{reference}/receipt
     * Upload receipt → THEN notify admin with booking details + receipt info.
     */
    public function upload(Request $request, string $reference)
    {
        $reservation = Reservation::where('reference_number', $reference)->firstOrFail();

        $request->validate([
            'receipt' => [
                'required', 'file', 'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'receipt.required' => 'Please select a receipt image to upload.',
            'receipt.image'    => 'The file must be an image.',
            'receipt.mimes'    => 'Only JPG, PNG, or WEBP images are allowed.',
            'receipt.max'      => 'Image must be less than 5MB.',
        ]);

        // Delete old receipt if exists
        if ($reservation->receipt_path && Storage::disk('public')->exists($reservation->receipt_path)) {
            Storage::disk('public')->delete($reservation->receipt_path);
        }

        // Store receipt
        $path = $request->file('receipt')->store('receipts', 'public');

        $reservation->update([
            'receipt_path'        => $path,
            'receipt_uploaded_at' => now(),
        ]);

        // ── NOW send admin notification email ──
        // This is the trigger: receipt uploaded = admin gets notified
        $adminEmail = env('ADMIN_EMAIL');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewReservationMail($reservation));
                Log::info("Admin notified after receipt upload: {$reservation->reference_number}");
            } catch (\Exception $e) {
                Log::error("Admin email failed: " . $e->getMessage());
            }
        }

        return back()->with('receipt_success', 'Receipt uploaded! The admin will review and confirm your booking shortly.');
    }
}