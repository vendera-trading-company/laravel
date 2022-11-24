<?php

namespace VenderaTradingCompany\App\Support;

use VenderaTradingCompany\App\Livewire\View;

abstract class Query
{
    public abstract function handle(View $view, array $context);

    public static function get(View $view, mixed $class, array $context = [])
    {
        $query = eval("return new {$class};");

        return $query->handle($view, $context);
    }
}
