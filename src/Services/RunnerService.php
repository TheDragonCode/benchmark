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

    /**
     * Executes a callback function and measures execution time and memory usage.
     *
     * @param  Closure  $callback  The callback function to execute.
     * @param  array  $parameters  Parameters to pass to the callback.
     * @return array An array [time in milliseconds, memory in bytes].
     */
    public function call(Closure $callback, array $parameters = []): array
    {
        $this->clean();

        return $this->run($callback, $parameters);
    }

    /**
     * Resets the memory state before measurement.
     */
    protected function clean(): void
    {
        $this->memory->reset();
    }

    /**
     * Runs a callback function and returns the measurement results.
     *
     * @param  Closure  $callback  The callback function to execute.
     * @param  array  $parameters  Parameters to pass to the callback.
     * @return array An array [time in milliseconds, memory in bytes].
     */
    protected function run(Closure $callback, array $parameters = []): array
    {
        $memoryFrom = $this->memory->now();
        $startAt    = hrtime(true);

        $callback(...$parameters);

        $time   = $this->diff(hrtime(true), $startAt);
        $memory = $this->memory->diff($memoryFrom);

        return [$time, $memory];
    }

    /**
     * Calculates the time difference between two hrtime values.
     *
     * @param  float  $now  The current hrtime value is specified in nanoseconds.
     * @param  float  $startAt  The initial hrtime value is specified in nanoseconds.
     * @return float The difference is specified in milliseconds.
     */
    protected function diff(float $now, float $startAt): float
    {
        return ($now - $startAt) / 1e+6;
    }
}
