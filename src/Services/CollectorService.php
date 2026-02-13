<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

class CollectorService
{
    protected array $data = [];

    public function push(string|int $name, array $values): self
    {
        $this->data[$name][] = $values;

        return $this;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function clear(): void
    {
        $this->data = [];
    }
}
