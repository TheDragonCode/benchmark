<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\Data\DeviationData;
use DragonCode\Benchmark\Data\MetricData;
use DragonCode\Benchmark\Data\ResultData;

use function array_map;
use function array_sum;
use function count;
use function sqrt;

class DeviationService
{
    public function __construct(
        protected ResultService $result = new ResultService,
    ) {}

    /**
     * @param  array<int, array<int|string, ResultData>>  $collection
     *
     * @return array
     */
    public function calculate(array $collection): array
    {
        return $this->map(
            $this->flatten($collection)
        );
    }

    protected function map(array $collection): array
    {
        return array_map(fn (array $item): ResultData => $this->make($item), $collection);
    }

    protected function make(array $item): ResultData
    {
        return new ResultData(
            min      : $this->metric($item, 'min'),
            max      : $this->metric($item, 'max'),
            avg      : $this->metric($item, 'avg'),
            total    : $this->metric($item, 'total'),
            deviation: $this->deviationMetric($item),
        );
    }

    protected function metric(array $item, string $key): MetricData
    {
        return $this->result->$key(
            $this->result->values($item[$key], 0, false),
            $this->result->values($item[$key], 1, false),
        );
    }

    protected function deviationMetric(array $item): DeviationData
    {
        $time   = $this->result->values($item['avg'], 0, false);
        $memory = $this->result->values($item['avg'], 1, false);

        return new DeviationData(
            percent: $this->metricData(
                $this->deviation($time),
                $this->deviation($memory),
            ),
        );
    }

    protected function metricData(float $time, float $memory): MetricData
    {
        return new MetricData($time, $memory);
    }

    protected function flatten(array $collection): array
    {
        $result = [];

        foreach ($collection as $items) {
            foreach ($items as $key => $item) {
                $result[$key]['min'][]   = [$item->min->time, $item->min->memory];
                $result[$key]['max'][]   = [$item->max->time, $item->max->memory];
                $result[$key]['avg'][]   = [$item->avg->time, $item->avg->memory];
                $result[$key]['total'][] = [$item->total->time, $item->total->memory];
            }
        }

        return $result;
    }

    protected function deviation(array $values): float
    {
        $avg = array_sum($values) / count($values);

        foreach ($values as &$value) {
            $value = ($value - $avg) ** 2;
        }

        return sqrt(array_sum($values) / count($values));
    }
}
