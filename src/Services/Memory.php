<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

class Memory
{
    protected array $sizes = [
        'GB' => 1024 * 1024 * 1024,
        'MB' => 1024 * 1024,
        'KB' => 1024,
    ];

    public function now(): int
    {
        return memory_get_peak_usage(true);
    }

    public function diff(int $memory): int
    {
        return memory_get_peak_usage(true) - $memory;
    }

    public function format(int $bytes): string
    {
        foreach ($this->sizes as $unit => $value) {
            if ($bytes >= $value) {
                return sprintf('%.2f %s', $bytes >= 1024 ? $bytes / $value : $bytes, $unit);
            }
        }

        return $bytes . ' byte' . ($bytes !== 1 ? 's' : '');
    }
}
