<?php

namespace VenderaTradingCompany\App\Livewire\View\Components;

use Illuminate\Support\Facades\URL;
use VenderaTradingCompany\App\Livewire\View;
use VenderaTradingCompany\App\Support\Collection;

abstract class ComponentBuilder
{
    public string $id;
    public View $view;
    public mixed $value = null;
    public array $listener = [];
    public bool $queryable = false;
    public mixed $class;

    public function __construct(View $view, string $id)
    {
        $this->id = $id;
        $this->view = $view;
        $this->value = null;
    }

    public function queryable(): self
    {
        $this->queryable = true;

        return $this;
    }

    public function value(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function listen(string $type, string $callback): self
    {
        $this->listener = Collection::create($this->listener)->add([
            'type' => $type,
            'callback' => $callback,
        ])->get();

        return $this;
    }

    public function build(): mixed
    {
        $queryData = [];

        if ($this->queryable) {
            $exploded = explode('?', URL::full());
            $parameters = [];

            if (!empty($exploded[1])) {
                $parameters = explode('&', $exploded[1]);
            }

            foreach ($parameters as $parameterKey => $parameterValue) {
                $tmp = explode('=', $parameterValue);

                if ($tmp[0] == $this->id) {
                    $value = $tmp[1];

                    $value = urldecode($value);

                    $queryData = [
                        'value' => $value,
                    ];
                }
            }
        }

        $componentData =  array_merge([
            'id' => $this->id,
            'value' => $this->value,
            'listener' => $this->listener,
            'queryable' => $this->queryable,
        ], $queryData);

        $component = new $this->class($this->view, $this->id, $componentData);

        return $this->view->createComponent($component, $this->class);
    }
}
