<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use function gc_collect_cycles;
use function memory_get_peak_usage;
use function memory_reset_peak_usage;
use function sprintf;

class MemoryService
{
    protected array $sizes = [
        'TB' => 1024 * 1024 * 1024 * 1024,
        'GB' => 1024 * 1024 * 1024,
        'MB' => 1024 * 1024,
        'KB' => 1024,
    ];

    /**
     * Returns the current peak memory usage.
     *
     * @return int  The value is specified in bytes.
     */
    public function now(): int
    {
        return memory_get_peak_usage(true);
    }

    /**
     * Calculates the difference between the current peak memory usage and the provided value.
     *
     * @param  int  $memory  The initial memory value is specified in bytes.
     *
     * @return int  The difference is specified in bytes.
     */
    public function diff(int $memory): int
    {
        return memory_get_peak_usage(true) - $memory;
    }

    /**
     * Resets peak memory usage and runs the garbage collector.
     */
    public function reset(): void
    {
        gc_collect_cycles();
        memory_reset_peak_usage();
    }

    /**
     * Formats a memory value into a human-readable format.
     *
     * @param  int  $bytes  The memory value is specified in bytes.
     *
     * @return string  A formatted string (e.g., "1.50 MB").
     */
    public function format(int $bytes): string
    {
        foreach ($this->sizes as $unit => $value) {
            if ($bytes >= $value) {
                return sprintf('%.2f %s', $bytes / $value, $unit);
            }
        }

        return $bytes . ' byte' . ($bytes !== 1 ? 's' : '');
    }
}
