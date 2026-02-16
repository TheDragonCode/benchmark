<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

class CollectorService
{
    protected array $data = [];

    /**
     * Adds measurement results for the specified name.
     *
     * @param  int|string  $name  The callback name.
     * @param  array  $values  An array of values [time in milliseconds, memory in bytes].
     * @return $this
     */
    public function push(int|string $name, array $values): static
    {
        $this->data[$name][] = $values;

        return $this;
    }

    /**
     * Returns all collected measurement data.
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Clears all collected data.
     */
    public function clear(): void
    {
        $this->data = [];
    }
}
