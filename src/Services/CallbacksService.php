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

    public function performBefore(string|int $name): mixed
    {
        return $this->perform($this->before, $name);
    }

    public function performBeforeEach(string|int $name, int $iteration): mixed
    {
        return $this->perform($this->beforeEach, $name, $iteration);
    }

    public function performAfter(string|int $name): mixed
    {
        return $this->perform($this->after, $name);
    }

    public function performAfterEach(string|int $name, int $iteration, float $time, float $memory): mixed
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
