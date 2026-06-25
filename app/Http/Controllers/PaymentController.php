<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Services\PayMongoService;
use App\Mail\BookingConfirmedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private PayMongoService $paymongo) {}

    /**
     * POST /payment/gcash/{reference}
     * Initiate GCash payment → redirect to GCash app.
     */
    public function initiateGcash(string $reference)
    {
        $reservation = Reservation::where('reference_number', $reference)->firstOrFail();

        // Already paid — skip
        if ($reservation->payment_status === 'paid') {
            return redirect()->route('reservations.confirmation', $reference)
                ->with('info', 'This booking is already paid.');
        }

        try {
            $result = $this->paymongo->createGcashSource(
                amountInPesos: $reservation->amount,
                description:   "WOLFPAX Court – {$reservation->reference_number}",
                successUrl:    route('payment.success', $reference) . '?source_id={source_id}',
                failedUrl:     route('payment.failed', $reference),
            );

            // Save source ID and payment URL
            $reservation->update([
                'paymongo_source_id' => $result['source_id'],
                'payment_url'        => $result['checkout_url'],
            ]);

            // Redirect customer to GCash
            return redirect($result['checkout_url']);

        } catch (\Exception $e) {
            Log::error('GCash initiation failed: ' . $e->getMessage());
            return redirect()->route('reservations.confirmation', $reference)
                ->with('error', 'GCash payment could not be initiated. Please try again or pay at the counter.');
        }
    }

    /**
     * GET /payment/success/{reference}
     * GCash redirects here after successful payment.
     * We verify the source is chargeable then create the payment.
     */
    public function success(Request $request, string $reference)
    {
        $reservation = Reservation::where('reference_number', $reference)->firstOrFail();
        $sourceId    = $request->query('source_id') ?? $reservation->paymongo_source_id;

        if (!$sourceId) {
            return redirect()->route('payment.failed', $reference);
        }

        try {
            $source = $this->paymongo->getSource($sourceId);
            $status = $source['attributes']['status'] ?? 'failed';

            if ($status === 'chargeable') {
                // Create the actual payment charge
                $payment = $this->paymongo->createPayment(
                    sourceId:      $sourceId,
                    amountInPesos: $reservation->amount,
                    description:   "WOLFPAX Court – {$reservation->reference_number}",
                );

                // Mark reservation as paid
                $reservation->update([
                    'payment_status'             => 'paid',
                    'payment_method'             => 'GCash',
                    'paymongo_payment_intent_id' => $payment['id'],
                    'paid_at'                    => now(),
                    'status'                     => 'confirmed',
                ]);

                // Send confirmation email to customer
                try {
                    Mail::to($reservation->email)->send(new BookingConfirmedMail($reservation));
                } catch (\Exception $e) {
                    Log::error('Confirmation email failed: ' . $e->getMessage());
                }

                return view('payment.success', compact('reservation'));

            } else {
                return redirect()->route('payment.failed', $reference);
            }

        } catch (\Exception $e) {
            Log::error('GCash payment verification failed: ' . $e->getMessage());
            return redirect()->route('payment.failed', $reference);
        }
    }

    /**
     * GET /payment/failed/{reference}
     * GCash redirects here if payment failed or was cancelled.
     */
    public function failed(string $reference)
    {
        $reservation = Reservation::where('reference_number', $reference)->firstOrFail();
        return view('payment.failed', compact('reservation'));
    }

    /**
     * POST /payment/webhook
     * PayMongo webhook — listens for source.chargeable events.
     */
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('Paymongo-Signature');

        if (!$this->paymongo->verifyWebhook($payload, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = json_decode($payload, true);
        $type  = $event['data']['attributes']['type'] ?? null;

        if ($type === 'source.chargeable') {
            $sourceId    = $event['data']['attributes']['data']['id'];
            $reservation = Reservation::where('paymongo_source_id', $sourceId)->first();

            if ($reservation && $reservation->payment_status !== 'paid') {
                try {
                    $payment = $this->paymongo->createPayment(
                        sourceId:      $sourceId,
                        amountInPesos: $reservation->amount,
                        description:   "WOLFPAX Court – {$reservation->reference_number}",
                    );

                    $reservation->update([
                        'payment_status'             => 'paid',
                        'paymongo_payment_intent_id' => $payment['id'],
                        'paid_at'                    => now(),
                        'status'                     => 'confirmed',
                    ]);

                    Mail::to($reservation->email)->send(new BookingConfirmedMail($reservation));

                } catch (\Exception $e) {
                    Log::error('Webhook payment charge failed: ' . $e->getMessage());
                }
            }
        }

        return response()->json(['received' => true]);
    }
}