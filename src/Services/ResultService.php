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

    /**
     * Checks whether results have already been calculated.
     *
     * @return bool
     */
    public function has(): bool
    {
        return $this->data !== null;
    }

    /**
     * Forcefully sets the results.
     *
     * @param  array  $collection  An array of results.
     */
    public function force(array $collection): void
    {
        $this->data = $collection;
    }

    /**
     * Returns benchmark results, calculating them on the first call.
     *
     * @param  array  $collections  Collected measurement data.
     *
     * @return ResultData[]
     */
    public function get(array $collections): array
    {
        return $this->data ??= $this->map($collections);
    }

    /**
     * Clears the stored results.
     */
    public function clear(): void
    {
        $this->data = null;
    }

    /**
     * Transforms collected measurement data into an array of ResultData.
     *
     * @param  array  $collections  Collected measurement data.
     *
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

    /**
     * Extracts values of the specified column from measurement data.
     *
     * @param  array  $data  An array of measurement data.
     * @param  int  $column  The column index (0 — time, 1 — memory).
     * @param  bool  $filter  Whether to apply measurement error filtering.
     *
     * @return array
     */
    public function values(array $data, int $column, bool $filter = true): array
    {
        $values = array_column($data, $column);

        return $filter ? $this->filter($values) : $values;
    }

    /**
     * Creates a ResultData object from time and memory arrays.
     *
     * @param  array  $times  An array of time values in milliseconds.
     * @param  array  $memory  An array of memory values in bytes.
     *
     * @return ResultData
     */
    protected function collect(array $times, array $memory): ResultData
    {
        return new ResultData(
            min  : $this->min($times, $memory),
            max  : $this->max($times, $memory),
            avg  : $this->avg($times, $memory),
            total: $this->total($times, $memory),
        );
    }

    /**
     * Calculates the minimum time and memory values.
     *
     * @param  array  $times  An array of time values in milliseconds.
     * @param  array  $memory  An array of memory values in bytes.
     *
     * @return MetricData
     */
    public function min(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : min($times),
            memory: min($memory),
        );
    }

    /**
     * Calculates the maximum time and memory values.
     *
     * @param  array  $times  An array of time values in milliseconds.
     * @param  array  $memory  An array of memory values in bytes.
     *
     * @return MetricData
     */
    public function max(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : max($times),
            memory: max($memory),
        );
    }

    /**
     * Calculates the average time and memory values.
     *
     * @param  array  $times  An array of time values in milliseconds.
     * @param  array  $memory  An array of memory values in bytes.
     *
     * @return MetricData
     */
    public function avg(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : array_sum($times)  / count($times),
            memory: array_sum($memory) / count($memory),
        );
    }

    /**
     * Calculates the total time and memory values.
     *
     * @param  array  $times  An array of time values in milliseconds.
     * @param  array  $memory  An array of memory values in bytes.
     *
     * @return MetricData
     */
    public function total(array $times, array $memory): MetricData
    {
        return $this->metric(
            time  : array_sum($times),
            memory: array_sum($memory),
        );
    }

    /**
     * Creates a MetricData object.
     *
     * @param  float  $time  Time value is specified in milliseconds.
     * @param  float  $memory  Memory value is specified in bytes.
     *
     * @return MetricData
     */
    protected function metric(float $time, float $memory): MetricData
    {
        return new MetricData($time, $memory);
    }

    /**
     * Filters values to reduce measurement error.
     *
     * @param  array  $values  An array of numeric values.
     *
     * @return array
     */
    protected function filter(array $values): array
    {
        return $this->measurement->filter($values);
    }
}
