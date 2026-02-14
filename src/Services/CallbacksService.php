<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use Closure;

class CallbacksService
{
    public ?Closure $before = null;

    public ?Closure $beforeEach = null;

    public ?Closure $after = null;

    public ?Closure $afterEach = null;

    public function performBefore(int|string $name): mixed
    {
        return $this->perform($this->before, $name);
    }

    public function performBeforeEach(int|string $name, int $iteration): mixed
    {
        return $this->perform($this->beforeEach, $name, $iteration);
    }

    public function performAfter(int|string $name): mixed
    {
        return $this->perform($this->after, $name);
    }

    public function performAfterEach(int|string $name, int $iteration, float $time, float $memory): mixed
    {
        return $this->perform($this->afterEach, $name, $iteration, $time, $memory);
    }

    protected function perform(?Closure $callback, mixed ...$args): mixed
    {
        if ($callback === null) {
            return null;
        }

        return $callback(...$args);
    }
}
