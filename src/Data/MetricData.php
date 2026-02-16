<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Data;

readonly class MetricData
{
    /**
     * Creates an object with time and memory metrics.
     *
     * When the metric is used to store deviations, the deviations are specified in percentages.
     *
     * @param  float  $time  Time value is specified in milliseconds.
     * @param  float  $memory  Memory value is specified in bytes.
     */
    public function __construct(
        public float $time,
        public float $memory,
    ) {}
}
