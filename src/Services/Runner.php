<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

class Runner
{
    public function __construct(
        protected readonly Memory $memory = new Memory()
    ) {
    }

    public function call(callable $callback): array
    {
        $this->clean();

        return $this->run($callback);
    }

    protected function clean(): void
    {
        gc_collect_cycles();
    }

    protected function run(callable $callback): array
    {
        $ramFrom = $this->memory->now();
        $startAt = hrtime(true);

        $callback();

        $time = $this->diff(hrtime(true), $startAt);
        $ram  = $this->memory->diff($ramFrom);

        return [$time, $ram];
    }

    protected function diff(float $now, float $startAt): float
    {
        return ($now - $startAt) / 1e+6;
    }
}
