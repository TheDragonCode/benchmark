<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Data;

readonly class ResultData
{
    public function __construct(
        public MetricData $min,
        public MetricData $max,
        public MetricData $avg,
        public MetricData $total,
        public ?DeviationData $deviation = null,
    ) {}
}
