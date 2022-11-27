<?php

namespace VenderaTradingCompany\App\Support;

use Illuminate\Support\Facades\Http;

class Payment
{
    public function create(int $amount, string $currency, array $options): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/payment/create', [
            'amount' => $amount,
            'currency' => $currency,
            'shipping_address' => $options['shipping_address'],
            'shipping_name' => $options['shipping_name'],
            'payment_method' => $options['payment_method'],
            'return_url' => $options['return_url'],
            'customer' => $options['customer'],
            'receipt_email' => $options['receipt_email'] ?? null,
        ]);

        if (!$response->ok()) {
            return null;
        }

        $status = $response->json('status');

        if ($status != 'done') {
            return null;
        }

        $data = $response->json('data');

        if (empty($data)) {
            return null;
        }

        return $data;
    }

    public function refund(string $paymenIntentId, int $amount): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/payment/refund', [
            'payment_intent_id' => $paymenIntentId,
            'amount' => $amount,
        ]);

        if (!$response->ok()) {
            return null;
        }

        $status = $response->json('status');

        if ($status != 'done') {
            return null;
        }

        $data = $response->json('data');

        if (empty($data)) {
            return null;
        }

        return $data;
    }

    public function get(string $paymenIntentId): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/payment/get', [
            'payment_intent_id' => $paymenIntentId,
        ]);

        if (!$response->ok()) {
            return null;
        }

        $status = $response->json('status');

        if ($status != 'done') {
            return null;
        }

        $data = $response->json('data');

        if (empty($data)) {
            return null;
        }

        return $data;
    }
}
