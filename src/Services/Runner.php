<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

class Runner
{
    public function call(callable $callback): float
    {
        $this->clean();

        return $this->run($callback);
    }

    protected function clean(): void
    {
        gc_collect_cycles();
    }

    protected function run(callable $callback): float
    {
        $startAt = hrtime(true);

        $callback();

        return $this->diff(hrtime(true), $startAt);
    }

    protected function diff(float $now, float $startAt): float
    {
        return ($now - $startAt) / 1e+6;
    }
}
