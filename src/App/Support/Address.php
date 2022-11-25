<?php

namespace VenderaTradingCompany\App\Support;

use Illuminate\Support\Facades\Http;

class Address
{
    public function country(string $country): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/address/country', [
            'country' => $country,
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
