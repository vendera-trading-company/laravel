<?php

namespace VenderaTradingCompany\App\Support;

use VenderaTradingCompany\App\Services\IpService;

class Ip
{
    public function country(): string|null
    {
        $data = IpService::resolve();

        $country = $data['country'];

        if (empty($country)) {
            return null;
        }

        return $country;
    }
}
