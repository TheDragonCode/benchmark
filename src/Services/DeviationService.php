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
    protected const MetricKeys  = ['min', 'max', 'avg', 'total'];
    protected const TimeIndex   = 0;
    protected const MemoryIndex = 1;

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
        $grouped = $this->group($collection);

        return array_map(fn (array $item): ResultData => $this->buildResultData($item), $grouped);
    }

    /**
     * Creates a ResultData object with metrics and deviations from grouped data.
     *
     * @param  array  $item  Grouped data for a single callback.
     */
    protected function buildResultData(array $item): ResultData
    {
        return new ResultData(
            min      : $this->buildMetric($item, 'min'),
            max      : $this->buildMetric($item, 'max'),
            avg      : $this->buildMetric($item, 'avg'),
            total    : $this->buildMetric($item, 'total'),
            deviation: $this->buildDeviation($item),
        );
    }

    /**
     * Calculates a metric (min, max, avg or total) from grouped data.
     *
     * @param  array  $item  Grouped data for a single callback.
     * @param  string  $key  The metric key (min, max, avg, total).
     */
    protected function buildMetric(array $item, string $key): MetricData
    {
        return $this->result->$key(
            $this->extract($item[$key], self::TimeIndex),
            $this->extract($item[$key], self::MemoryIndex),
        );
    }

    /**
     * Extracts time or memory values from grouped metric data.
     *
     * @param  array  $data  Grouped metric entries.
     * @param  int  $index  The index to extract (TIME_INDEX or MEMORY_INDEX).
     *
     * @return array<float>
     */
    protected function extract(array $data, int $index): array
    {
        return $this->result->values($data, $index, false);
    }

    /**
     * Calculates deviation data based on average values.
     *
     * @param  array  $item  Grouped data for a single callback.
     */
    protected function buildDeviation(array $item): DeviationData
    {
        $timeValues   = $this->extract($item['avg'], self::TimeIndex);
        $memoryValues = $this->extract($item['avg'], self::MemoryIndex);

        $avg = $this->buildMetric($item, 'avg');

        return new DeviationData(
            percent: new MetricData(
                $this->percentage($avg->time, $this->deviation($timeValues)),
                $this->percentage($avg->memory, $this->deviation($memoryValues)),
            ),
        );
    }

    /**
     * Groups results from multiple runs by callback names and metric types.
     *
     * @param  array<int, array<int|string, ResultData>>  $collection  A collection of results from multiple runs.
     *
     * @return array<int|string, array<string, array<int, array{float, float}>>>
     */
    protected function group(array $collection): array
    {
        $result = [];

        foreach ($collection as $items) {
            foreach ($items as $key => $item) {
                foreach (self::MetricKeys as $metric) {
                    $result[$key][$metric][] = [$item->$metric->time, $item->$metric->memory];
                }
            }
        }

        return $result;
    }

    /**
     * Calculates the standard deviation for an array of values.
     *
     * @param  array<float>  $values  An array of numeric values.
     */
    protected function deviation(array $values): float
    {
        $count = count($values);
        $avg   = array_sum($values) / $count;

        $squared = array_map(static fn (float $value): float => ($value - $avg) ** 2, $values);

        return sqrt(array_sum($squared) / $count);
    }

    /**
     * Calculates the percentage ratio of two values.
     *
     * @param  float  $value1  The base value.
     * @param  float  $value2  The compared value.
     *
     * @return float The result is specified in percentages.
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
