<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Data;

readonly class ResultData
{
    /**
     * Creates an object with benchmark results.
     *
     * @param  MetricData  $min  Minimum metric values.
     * @param  MetricData  $max  Maximum metric values.
     * @param  MetricData  $avg  Average metric values.
     * @param  MetricData  $total  Total metric values.
     * @param  DeviationData|null  $deviation  Deviation data.
     */
    public function __construct(
        public MetricData $min,
        public MetricData $max,
        public MetricData $avg,
        public MetricData $total,
        public ?DeviationData $deviation = null,
    ) {}
}
