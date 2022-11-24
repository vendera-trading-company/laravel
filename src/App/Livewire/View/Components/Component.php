<?php

namespace VenderaTradingCompany\App\Livewire\View\Components;

use VenderaTradingCompany\App\Livewire\View;

class Component
{
    public View $view;
    private string $id;
    private array $data;

    public function __construct(View $view, string $id, array $data)
    {
        $this->view = $view;
        $this->id = $id;
        $this->data = $data;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getValue(): mixed
    {
        return $this->data['value'];
    }

    public function getPath(): string
    {
        return 'data.components.' . $this->id . '.data.value';
    }

    public function setValue(mixed $value): self
    {
        $this->data['value'] = $value;

        return $this->update();
    }

    public function isQueryable(): bool
    {
        return $this->data['queryable'] ?? false;
    }

    public function update(): self
    {
        $this->view->updateComponent($this);

        return $this;
    }

    public function notifyListener()
    {
        if (empty($this->data['listener'] ?? null)) {
            return;
        }

        foreach ($this->data['listener'] as $key => $value) {
            if ($value['type'] == 'update') {
                $callback = $value['callback'];
                $this->view->$callback($this);
            }
        }
    }

    public function toArray(): array
    {
        return [
            'class' => static::class,
            'id' => $this->id,
            'data' => $this->data,
        ];
    }
}
