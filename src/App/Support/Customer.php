<?php

namespace VenderaTradingCompany\App\Support;

use Illuminate\Support\Facades\Http;

class Customer
{
    public function create(string $email, string $firstName, string $lastName): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/customer/create', [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
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

    public function get(string $email): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/customer/get', [
            'email' => $email,
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
