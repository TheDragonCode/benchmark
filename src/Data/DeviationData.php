<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Data;

readonly class DeviationData
{
    /**
     * Creates an object with deviation data.
     *
     * @param  MetricData  $percent  Deviation metrics are specified in percentages.
     */
    public function __construct(
        public MetricData $percent,
    ) {}
}
