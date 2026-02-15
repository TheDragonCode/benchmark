<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Data;

readonly class DeviationData
{
    public function __construct(
        public MetricData $percent,
    ) {}
}
