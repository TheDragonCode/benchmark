<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Data;

readonly class MetricData
{
    public function __construct(
        public float $time,
        public float $memory,
    ) {}
}
