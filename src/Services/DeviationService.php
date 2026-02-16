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
     * Calculates final results with deviations based on multiple runs.
     *
     * @param  array<int, array<int|string, ResultData>>  $collection  A collection of results from multiple runs.
     *
     * @return array<int|string, ResultData>
     */
    public function calculate(array $collection): array
    {
        return $this->map(
            $this->flatten($collection)
        );
    }

    /**
     * Transforms grouped data into an array of ResultData.
     *
     * @param  array  $collection  Grouped measurement data.
     *
     * @return array<int|string, ResultData>
     */
    protected function map(array $collection): array
    {
        return array_map(fn (array $item): ResultData => $this->make($item), $collection);
    }

    /**
     * Creates a ResultData object with metrics and deviations from grouped data.
     *
     * @param  array  $item  Grouped data for a single callback.
     *
     * @return ResultData
     */
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

    /**
     * Calculates a metric (min, max, avg or total) from grouped data.
     *
     * @param  array  $item  Grouped data for a single callback.
     * @param  string  $key  The metric key (min, max, avg, total).
     *
     * @return MetricData
     */
    protected function metric(array $item, string $key): MetricData
    {
        return $this->result->$key(
            $this->result->values($item[$key], 0, false),
            $this->result->values($item[$key], 1, false),
        );
    }

    /**
     * Calculates deviation data based on average values.
     *
     * @param  array  $item  Grouped data for a single callback.
     *
     * @return DeviationData
     */
    protected function deviationMetric(array $item): DeviationData
    {
        $time   = $this->result->values($item['avg'], 0, false);
        $memory = $this->result->values($item['avg'], 1, false);

        $avg = $this->metric($item, 'avg');

        return new DeviationData(
            percent: $this->metricData(
                $this->percentage($avg->time, $this->deviation($time)),
                $this->percentage($avg->memory, $this->deviation($memory)),
            ),
        );
    }

    /**
     * Creates a MetricData object with the specified values.
     *
     * @param  float  $time  Time value is specified in milliseconds.
     * @param  float  $memory  Memory value is specified in bytes.
     *
     * @return MetricData
     */
    protected function metricData(float $time, float $memory): MetricData
    {
        return new MetricData($time, $memory);
    }

    /**
     * Groups results from multiple runs by callback names and metric types.
     *
     * @param  array  $collection  A collection of results from multiple runs.
     *
     * @return array
     */
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

    /**
     * Calculates the standard deviation for an array of values.
     *
     * @param  array  $values  An array of numeric values.
     *
     * @return float
     */
    protected function deviation(array $values): float
    {
        $avg = array_sum($values) / count($values);

        foreach ($values as &$value) {
            $value = ($value - $avg) ** 2;
        }

        return sqrt(array_sum($values) / count($values));
    }

    /**
     * Calculates the percentage ratio of two values.
     *
     * @param  float  $value1  The base value.
     * @param  float  $value2  The compared value.
     *
     * @return float  The result is specified in percentages.
     */
    protected function percentage(float $value1, float $value2): float
    {
        if (! $value1 && ! $value2) {
            return 0;
        }

        if ($value1 === 0.0) {
            return INF;
        }

        return ($value2 / $value1) * 100;
    }
}
