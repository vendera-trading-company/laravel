<?php

namespace VenderaTradingCompany\App\Support;

use Illuminate\Support\Facades\Http;

class Card
{
    public function create(string $customerId, string $number, string $cvc, string $expMonth, string $expYear): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/card/create', [
            'customer_id' => $customerId,
            'number' => $number,
            'cvc' => $cvc,
            'exp_month' => $expMonth,
            'exp_year' => $expYear,
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

    public function all(string $customerId): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/card/all', [
            'customer_id' => $customerId,
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

    public function delete(string $customerId, string $cardSourceId): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/card/delete', [
            'customer_id' => $customerId,
            'card_source_id' => $cardSourceId,
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

    public function update(string $customerId, string $cardSourceId, array $data = []): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/card/delete', [
            'customer_id' => $customerId,
            'card_source_id' => $cardSourceId,
            'name' => $data['name'] ?? null,
            'exp_month' => $data['exp_month'] ?? null,
            'exp_year' => $data['exp_year'] ?? null,
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
