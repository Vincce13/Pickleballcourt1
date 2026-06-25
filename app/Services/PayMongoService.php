<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayMongoService
{
    private string $baseUrl  = 'https://api.paymongo.com/v1';
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key');
    }

    /**
     * Create a GCash Source (payment link).
     * Returns the checkout URL and source ID.
     */
    public function createGcashSource(int $amountInPesos, string $description, string $successUrl, string $failedUrl): array
    {
        $amountInCentavos = $amountInPesos * 100;

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post("{$this->baseUrl}/sources", [
                'data' => [
                    'attributes' => [
                        'amount'      => $amountInCentavos,
                        'currency'    => 'PHP',
                        'type'        => 'gcash',
                        'description' => $description,
                        'redirect'    => [
                            'success' => $successUrl,
                            'failed'  => $failedUrl,
                        ],
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new \Exception('PayMongo GCash source creation failed: ' . $response->body());
        }

        $data = $response->json('data');

        return [
            'source_id'   => $data['id'],
            'checkout_url'=> $data['attributes']['redirect']['checkout_url'],
            'status'       => $data['attributes']['status'],
        ];
    }

    /**
     * Create a Payment from a chargeable source.
     */
    public function createPayment(string $sourceId, int $amountInPesos, string $description): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->post("{$this->baseUrl}/payments", [
                'data' => [
                    'attributes' => [
                        'amount'      => $amountInPesos * 100,
                        'currency'    => 'PHP',
                        'description' => $description,
                        'source'      => [
                            'id'   => $sourceId,
                            'type' => 'source',
                        ],
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new \Exception('PayMongo payment creation failed: ' . $response->body());
        }

        return $response->json('data');
    }

    /**
     * Retrieve a source by ID to check status.
     */
    public function getSource(string $sourceId): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->get("{$this->baseUrl}/sources/{$sourceId}");

        if ($response->failed()) {
            throw new \Exception('PayMongo get source failed: ' . $response->body());
        }

        return $response->json('data');
    }

    /**
     * Verify a webhook signature.
     */
    public function verifyWebhook(string $payload, string $signature): bool
    {
        $secret   = config('services.paymongo.webhook_secret');
        $expected = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expected, $signature);
    }
}