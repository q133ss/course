<?php

namespace App\Services\Payments;

use App\Services\Payments\Exceptions\PaymentException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class YooKassaService
{
    private const BASE_URL = 'https://api.yookassa.ru/v3';

    private readonly string $shopId;

    private readonly string $secretKey;

    private readonly ?string $defaultReturnUrl;

    public function __construct()
    {
        $this->shopId = (string) config('services.yookassa.shop_id');
        $this->secretKey = (string) config('services.yookassa.secret_key');
        $this->defaultReturnUrl = config('services.yookassa.return_url');

        if ($this->shopId === '' || $this->secretKey === '') {
            throw new PaymentException('YooKassa credentials are not configured.');
        }
    }

    /**
     * Create a payment and return the YooKassa payload.
     *
     * @param  float|int|string  $amount
     * @param  string  $description
     * @param  string|null  $returnUrl
     * @param  array<string, mixed>  $metadata
     * @return array<string, mixed>
     *
     * @throws PaymentException
     * @throws RequestException
     */
    public function createPayment(float|int|string $amount, string $description, ?string $returnUrl = null, array $metadata = []): array
    {
        $payload = [
            'amount' => [
                'value' => $this->formatAmount($amount),
                'currency' => 'RUB',
            ],
            'capture' => true,
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => $returnUrl ?? $this->defaultReturnUrl,
            ],
            'description' => $description,
        ];

        if (! empty($metadata)) {
            $payload['metadata'] = $metadata;
        }

        if (($payload['confirmation']['return_url'] ?? null) === null) {
            throw new PaymentException('YooKassa return URL is not configured.');
        }

        $response = $this->httpClient()
            ->withHeaders(['Idempotence-Key' => (string) Str::uuid()])
            ->post('/payments', $payload)
            ->throw();

        return $response->json();
    }

    /**
     * Retrieve payment details by id.
     *
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function getPayment(string $paymentId): array
    {
        $response = $this->httpClient()
            ->get('/payments/' . $paymentId)
            ->throw();

        return $response->json();
    }

    private function httpClient(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL)
            ->acceptJson()
            ->withBasicAuth($this->shopId, $this->secretKey);
    }

    private function formatAmount(float|int|string $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }
}
