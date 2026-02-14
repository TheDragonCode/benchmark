<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use Closure;

use function hrtime;

class RunnerService
{
    public function __construct(
        protected readonly MemoryService $memory = new MemoryService
    ) {}

    public function call(Closure $callback, array $parameters = []): array
    {
        $this->clean();

        return $this->run($callback, $parameters);
    }

    protected function clean(): void
    {
        $this->memory->reset();
    }

    protected function run(Closure $callback, array $parameters = []): array
    {
        $memoryFrom = $this->memory->now();
        $startAt    = hrtime(true);

        $callback(...$parameters);

        $time   = $this->diff(hrtime(true), $startAt);
        $memory = $this->memory->diff($memoryFrom);

        return [$time, $memory];
    }

    protected function diff(float $now, float $startAt): float
    {
        return ($now - $startAt) / 1e+6;
    }
}
