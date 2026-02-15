<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\Data\DeviationData;
use DragonCode\Benchmark\Data\MetricData;
use DragonCode\Benchmark\Data\ResultData;

use function array_map;
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
        return new DeviationData(
            avg: $this->result->avg(
                $this->result->values($item['deviation'], 0, false),
                $this->result->values($item['deviation'], 1, false),
            ),
        );
    }

    protected function flatten(array $collection): array
    {
        $default = [];
        $result  = [];

        foreach ($collection as $i => $items) {
            foreach ($items as $key => $item) {
                $result[$key]['min'][]   = [$item->min->time, $item->min->memory];
                $result[$key]['max'][]   = [$item->max->time, $item->max->memory];
                $result[$key]['avg'][]   = [$item->avg->time, $item->avg->memory];
                $result[$key]['total'][] = [$item->total->time, $item->total->memory];

                if ($i === 0) {
                    $default[$key] = [$item->avg->time, $item->avg->memory];

                    continue;
                }

                $result[$key]['deviation'][] = [
                    $this->percentage($default[$key][0], $this->deviation($default[$key][0], $item->avg->time)),
                    $this->percentage($default[$key][1], $this->deviation($default[$key][1], $item->avg->memory)),
                ];
            }
        }

        return $result;
    }

    protected function deviation(float $first, float $second): float
    {
        $avg = ($first + $second) / 2;

        $deviation1 = ($first - $avg) ** 2;
        $deviation2 = ($second - $avg) ** 2;

        return sqrt(($deviation1 + $deviation2) / 2);
    }

    protected function percentage(float $reference, float $value): float
    {
        if ($reference === 0.0) {
            return 0;
        }

        return ($value - $reference) / $reference * 100;
    }
}
