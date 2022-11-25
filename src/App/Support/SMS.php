<?php

namespace VenderaTradingCompany\App\Support;

use Illuminate\Support\Facades\Http;

class SMS
{
    public function send(string $from, string $to, string $message, string|null $callbackUrl = null): array|null
    {
        $response = Http::post('https://api.vendera-trading.company/sms/send', [
            'from' => $from,
            'to' => $to,
            'message' => $message,
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
