<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

use DragonCode\Benchmark\Data\MetricData;

use function sprintf;

class ResultTransformer
{
    /**
     * @param  \DragonCode\Benchmark\Data\ResultData[]  $collection
     */
    public function show(array $collection): array
    {
        $table = [];

        foreach ($collection as $key => $item) {
            $table['#'][]     = $key;
            $table['min'][]   = $this->value($item->min);
            $table['max'][]   = $this->value($item->max);
            $table['avg'][]   = $this->value($item->avg);
            $table['total'][] = $this->value($item->total);
        }

        return $table;
    }

    protected function value(MetricData $metric): string
    {
        return sprintf('%d ms - %s b', $metric->time, $metric->memory);
    }
}
