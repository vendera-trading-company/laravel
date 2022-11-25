<?php

namespace VenderaTradingCompany\App\Facades;

use Illuminate\Support\Facades\Facade;

class SMS extends Facade
{

    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'vendera-trading-company-sms'; }
}
