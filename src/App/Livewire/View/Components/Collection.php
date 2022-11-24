<?php

namespace VenderaTradingCompany\App\Livewire\View\Components;

use VenderaTradingCompany\App\Livewire\View;
use VenderaTradingCompany\App\Support\Collection as SupportCollection;

class Collection extends Component
{
    public static function create(View $view, string $id): CollectionBuilder
    {
        return new CollectionBuilder($view, $id);
    }

    public function get(string $key = ''): mixed
    {
        return SupportCollection::create($this->getValue())->get($key);
    }

    public function set(string $key, mixed $value): self
    {
        $this->setValue(SupportCollection::create($this->getValue())->set($key, $value)->get());

        return $this;
    }

    public function remove(mixed $value): self
    {
        $this->setValue(SupportCollection::create($this->getValue())->remove($value)->get());

        return $this;
    }

    public function removeAt(mixed $key): self
    {
        $this->setValue(SupportCollection::create($this->getValue())->removeAt($key)->get());

        return $this;
    }

    public function add(mixed $value): self
    {
        $this->setValue(SupportCollection::create($this->getValue())->add($value)->get());

        return $this;
    }

    public function up(mixed $key): self
    {
        $this->setValue(SupportCollection::create($this->getValue())->up($key)->get());

        return $this;
    }

    public function down(mixed $key): self
    {
        $this->setValue(SupportCollection::create($this->getValue())->down($key)->get());

        return $this;
    }

    public function has(mixed $value): bool
    {
        return SupportCollection::create($this->getValue())->has($value);
    }

    public function empty(): bool
    {
        return SupportCollection::create($this->getValue())->empty();
    }

    public function clear(): self
    {
        return $this->setValue([]);
    }

    public function count(): int
    {
        return SupportCollection::create($this->getValue())->count();
    }

    public function array(): array
    {
        if ($this->empty()) {
            return [];
        }

        return $this->getValue();
    }
}
