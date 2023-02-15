<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

class Memory
{
    protected array $sizes = [
        'GB' => 1073741824,
        'MB' => 1048576,
        'KB' => 1024,
    ];

    public function start(): int
    {
        return memory_get_usage(true);
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
