<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Data;

readonly class ResultData
{
    /**
     * Creates an object with benchmark results.
     *
     * @param  MetricData  $min  Minimum metric values. Time is specified in milliseconds, memory in bytes.
     * @param  MetricData  $max  Maximum metric values. Time is specified in milliseconds, memory in bytes.
     * @param  MetricData  $avg  Average metric values. Time is specified in milliseconds, memory in bytes.
     * @param  MetricData  $total  Total metric values. Time is specified in milliseconds, memory in bytes.
     * @param  DeviationData|null  $deviation  Deviation data. Values are specified in percentages.
     */
    public function __construct(
        public MetricData $min,
        public MetricData $max,
        public MetricData $avg,
        public MetricData $total,
        public ?DeviationData $deviation = null,
    ) {}
}
