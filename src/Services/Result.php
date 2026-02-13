<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\Data\MetricData;
use DragonCode\Benchmark\Data\ResultData;

use function array_column;
use function array_map;
use function array_slice;
use function array_sum;
use function count;
use function max;
use function min;

class Result
{
    protected ?array $data = null;

    /**
     * @return \DragonCode\Benchmark\Data\ResultData[]
     */
    public function get(array $collections): array
    {
        return $this->data ??= array_map(function (array $data): ResultData {
            return $this->collect(
                $this->times($data),
                $this->memory($data)
            );
        }, $collections);
    }

    public function clear(): void
    {
        $this->data = null;
    }

    protected function times(array $data): array
    {
        return $this->filter(
            array_column($data, 0)
        );
    }

    protected function memory(array $data): array
    {
        return $this->filter(
            array_column($data, 1)
        );
    }

    protected function collect(array $times, array $memory): ResultData
    {
        return new ResultData(
            min  : $this->min($times, $memory),
            max  : $this->max($times, $memory),
            avg  : $this->avg($times, $memory),
            total: $this->total($times, $memory),
        );
    }

    protected function min(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : min($times),
            memory: min($memory),
        );
    }

    protected function max(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : max($times),
            memory: max($memory),
        );
    }

    protected function avg(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : array_sum($times) / count($times),
            memory: array_sum($memory) / count($memory),
        );
    }

    protected function total(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : array_sum($times),
            memory: array_sum($memory),
        );
    }

    protected function metric(float $time, float $memory): MetricData
    {
        return new MetricData($time, $memory);
    }

    protected function filter(array $values): array
    {
        $count = count($values);

        if ($count < 10) {
            return $values;
        }

        $skip = (int) ($count * 0.1);

        return array_slice(
            array : $values,
            offset: $skip + 1,
            length: $count - ($skip - 1) * 2
        );
    }
}
