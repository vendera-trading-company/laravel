<?php

namespace VenderaTradingCompany\App\Support;

class Collection
{
    private array $data = [];

    public function __construct(mixed $data = [])
    {
        if ($data == null) {
            $data = [];
        }

        $this->data = $data;
    }

    public static function create(mixed $data = []): Collection
    {
        return new Collection($data);
    }

    public function empty(): bool
    {
        return empty($this->data);
    }

    public function clear(): self
    {
        $this->data = [];

        return $this;
    }

    public function down(mixed $key): self
    {
        $keys = array_keys($this->data);

        for ($i = 0; $i < $this->count(); $i++) {
            if ($keys[$i] == $key) {
                if ($i == 0) {
                    break;
                }
                $current = $this->get($keys[$i]);
                $prev = $this->get($keys[$i - 1]);

                $this->set($keys[$i], $prev);
                $this->set($keys[$i - 1], $current);
                break;
            }
        }

        return $this;
    }

    public function up(mixed $key): self
    {
        $keys = array_keys($this->data);

        for ($i = 0; $i < $this->count(); $i++) {
            if ($keys[$i] == $key) {
                if ($i == count($keys) - 1) {
                    break;
                }
                $current = $this->get($keys[$i]);
                $next = $this->get($keys[$i + 1]);

                $this->set($keys[$i], $next);
                $this->set($keys[$i + 1], $current);
                break;
            }
        }

        return $this;
    }

    public function set(mixed $key, mixed $value): self
    {
        $segments = [];

        if (is_string($key)) {
            $segments = explode('.', $key);
        } else {
            $segments = [
                $key,
            ];
        }

        $counter = 0;

        $dataset = [];

        while ($counter < count($segments)) {
            array_push($dataset, []);
            $counter++;
        }

        $data = $this->data;

        foreach ($segments as $index => $segment) {
            if (is_array($data[$segment] ?? []) && array_is_list($data[$segment] ?? []) && is_numeric($segment)) {
                $segment = intval($segment);
            }

            if (!key_exists($segment, $data ?? [])) {
                if ($index == count($segments) - 1) {
                    $data[$segment] = $value;
                } else {
                    $data[$segment] = [];
                }
            }

            if ($index == count($segments) - 1) {
                $data[$segment] = $value;
            }

            $dataset[$index] = $data;

            $data = $data[$segment];
        }

        for ($i = count($dataset); $i -= 1; $i > 0) {
            if ($i >= 1) {
                if (is_array($dataset[$i]) && array_is_list($dataset[$i]) && is_numeric($segments[$i - 1])) {
                    $dataset[$i - 1][intval($segments[$i - 1])] = array_values($dataset[$i]);
                } else {
                    $dataset[$i - 1][$segments[$i - 1]] = $dataset[$i];
                }
            }
        }

        $this->data = $dataset[0];

        return $this;
    }

    public function get(mixed $key = '', mixed $default = null): mixed
    {
        if ($this->empty()) {
            return [];
        }

        if ($key === null || $key === '') {

            return $this->data;
        }

        $segments = explode('.', $key);

        $data = $this->data;

        foreach ($segments as $index => $segment) {
            $value = $data[$segment] ?? null;

            if ($index == count($segments) - 1) {
                return $value;
            }

            if ($value == null) {
                return null;
            }

            if (!is_array($value)) {
                return null;
            }

            $data = $value;
        }
    }

    public function has(mixed $value): bool
    {
        return in_array($value, $this->data);
    }

    public function add(mixed $value): self
    {
        array_push($this->data, $value);

        return $this;
    }



    public function remove(mixed $search): self
    {
        if ($this->empty()) {
            return $this;
        }

        $result = [];

        if (array_is_list($this->data)) {
            foreach ($this->data as $key => $value) {
                if ($value != $search) {
                    array_push($result, $value);
                }
            }
        } else {
            foreach ($this->data as $key => $value) {
                if ($key != $search) {
                    $result[$key] = $value;
                }
            }
        }

        $this->data = $result;

        return $this;
    }

    public function removeAt(mixed $search): self
    {
        if ($this->empty()) {
            return $this;
        }

        $result = [];

        if (array_is_list($this->data)) {
            foreach ($this->data as $key => $value) {
                if ($key != $search) {
                    array_push($result, $value);
                }
            }
        } else {
            foreach ($this->data as $key => $value) {
                if ($key != $search) {
                    $result[$key] = $value;
                }
            }
        }

        $this->data = $result;

        return $this;
    }

    public function map(mixed $callback): self
    {
        $mapped = [];

        foreach ($this->data as $key => $value) {
            $mapped[$key] = $callback($value);
        }

        return Collection::create($mapped);
    }

    public function count(): int
    {
        if ($this->empty()) {
            return 0;
        }

        return count($this->data);
    }
}
