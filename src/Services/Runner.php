<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

class Runner
{
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
        $ramFrom = memory_get_usage();
        $startAt = hrtime(true);

        $callback();

        $time = $this->diff(hrtime(true), $startAt);
        $ram  = memory_get_peak_usage() - $ramFrom;

        return [$time, $ram];
    }

    protected function diff(float $now, float $startAt): float
    {
        return ($now - $startAt) / 1e+6;
    }
}
