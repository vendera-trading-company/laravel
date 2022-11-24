<?php

namespace VenderaTradingCompany\App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Ip
{
    public function resolve(string|null $ip = null): array|null
    {
        if (empty($ip)) {
            $ip = request()->ip();
        }

        $cacheKey = 'vendera_trading_company_' . str_replace('.', '_', $ip);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::get('https://api.vendera-trading.company/ip/resolve', [
            'ip' => $ip,
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

        Cache::put($cacheKey, $data, now()->addHour());

        return $data;
    }

    public function country(string|null $ip = null, string|null $default = null): string|null
    {
        $data = $this->resolve($ip);

        $country = $data['country'];

        if (empty($country)) {
            return $default;
        }

        return $country;
    }
}
