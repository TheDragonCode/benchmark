<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\Data\MetricData;
use DragonCode\Benchmark\Data\ResultData;

use function array_column;
use function array_map;
use function array_sum;
use function count;
use function max;
use function min;

class ResultService
{
    protected ?array $data = null;

    public function __construct(
        protected MeasurementErrorService $measurement = new MeasurementErrorService,
    ) {}

    public function has(): bool
    {
        return $this->data !== null;
    }

    public function force(array $collection): void
    {
        $this->data = $collection;
    }

    /**
     * @return ResultData[]
     */
    public function get(array $collections): array
    {
        return $this->data ??= $this->map($collections);
    }

    public function clear(): void
    {
        $this->data = null;
    }

    /**
     * @return ResultData[]
     */
    public function map(array $collections): array
    {
        return array_map(function (array $data): ResultData {
            return $this->collect(
                $this->values($data, 0),
                $this->values($data, 1)
            );
        }, $collections);
    }

    public function values(array $data, int $column, bool $filter = true): array
    {
        $values = array_column($data, $column);

        return $filter ? $this->filter($values) : $values;
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

    public function min(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : min($times),
            memory: min($memory),
        );
    }

    public function max(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : max($times),
            memory: max($memory),
        );
    }

    public function avg(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : array_sum($times) / count($times),
            memory: array_sum($memory) / count($memory),
        );
    }

    public function total(array $times, array $memory): MetricData
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
        return $this->measurement->filter($values);
    }
}
