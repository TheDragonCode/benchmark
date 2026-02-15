<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

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
        return array_map(fn (array $item) => $this->make($item), $collection);
    }

    protected function make(array $item): ResultData
    {
        dd($item);
        return new ResultData(
            min: $this->metric()
        );
    }

    protected function metric(float $time, float $memory): MetricData {}

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
                    $this->deviation($default[$key][0], $item->avg->time),
                    $this->deviation($default[$key][1], $item->avg->memory),
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
}
