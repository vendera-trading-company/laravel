<?php

namespace VenderaTradingCompany\App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IpService
{
    public static function resolve(string $ip = ''): array|null
    {
        $cacheKey = 'vendera_trading_company_' . str_replace('.', '_', $ip);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = null;

        if (empty($ip)) {
            $response = Http::get('https://api.vendera-trading.company/ip/resolve');
        } else {
            $response = Http::get('https://api.vendera-trading.company/ip/resolve', [
                'ip' => $ip,
            ]);
        }

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

        Cache::put($cacheKey, $data, now()->addHour());

        return $data;
    }
}
