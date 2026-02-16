<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

use DragonCode\Benchmark\Data\MetricData;
use DragonCode\Benchmark\Services\MemoryService;

use function array_keys;
use function asort;

class ResultTransformer
{
    protected ?int $precision = null;

    protected array $table = [
        ['#' => 'min'],
        ['#' => 'max'],
        ['#' => 'avg'],
        ['#' => 'total'],
        [null],
        ['#' => 'order'],
    ];

    public function __construct(
        protected MemoryService $memory = new MemoryService,
    ) {}

    /**
     * Sets the rounding precision for time values.
     *
     * @param  int|null  $precision  The number of decimal places. Null means no rounding.
     */
    public function round(?int $precision): void
    {
        $this->precision = $precision;
    }

    /**
     * Transforms a result collection into a table format for display.
     *
     * @param  \DragonCode\Benchmark\Data\ResultData[]  $collection
     *
     * @return array
     */
    public function toTable(array $collection): array
    {
        $table = $this->map($this->table, $collection);

        return $this->order($table, $collection);
    }

    /**
     * Fills the table with data from the result collection.
     *
     * @param  array  $table  The table template.
     * @param  \DragonCode\Benchmark\Data\ResultData[]  $collection
     *
     * @return array
     */
    protected function map(array $table, array $collection): array
    {
        foreach ($collection as $key => $item) {
            $table[0][$key] = $this->value($item->min);
            $table[1][$key] = $this->value($item->max);
            $table[2][$key] = $this->value($item->avg);
            $table[3][$key] = $this->value($item->total);
            $table[5][$key] = 0;

            if (! $deviation = $item->deviation?->percent) {
                continue;
            }

            $table[6] = [null];

            $table[7]['#'] = 'deviation time';
            $table[8]['#'] = 'deviation memory';

            $table[7][$key] = $this->deviation($deviation->time);
            $table[8][$key] = $this->deviation($deviation->memory);
        }

        return $table;
    }

    /**
     * Determines the order of callbacks by average execution time.
     *
     * @param  array  $table  The table with data.
     * @param  \DragonCode\Benchmark\Data\ResultData[]  $collection
     *
     * @return array
     */
    protected function order(array $table, array $collection): array
    {
        $values = [];

        foreach ($collection as $key => $item) {
            $values[$key] = $item->avg->time;
        }

        asort($values, SORT_NUMERIC);

        foreach (array_keys($values) as $index => $key) {
            $table[5][$key] = $index + 1;
        }

        return $table;
    }

    /**
     * Formats a metric into a string with time and memory.
     *
     * @param  MetricData  $metric  The metric. Time is specified in milliseconds, memory in bytes.
     *
     * @return string  A formatted string (e.g., "0.123 ms - 2.00 MB").
     */
    protected function value(MetricData $metric): string
    {
        $time   = $this->time($metric->time);
        $memory = $this->memory($metric->memory);

        return $memory
            ? sprintf('%s ms - %s', $time, $memory)
            : sprintf('%s ms', $time);
    }

    /**
     * Formats a deviation value into a string with percentages.
     *
     * @param  float  $value  The deviation value is specified in percentages.
     *
     * @return string  A formatted string (e.g., "+1.23%").
     */
    protected function deviation(float $value): string
    {
        $value = round($value, 2);

        return ($value > 0 ? '+' : '') . $value . '%';
    }

    /**
     * Rounds a time value with the specified precision.
     *
     * @param  float  $value  Time value is specified in milliseconds.
     *
     * @return float  The rounded value is specified in milliseconds.
     */
    protected function time(float $value): float
    {
        if ($this->precision === null) {
            return $value;
        }

        return round($value, $this->precision);
    }

    /**
     * Formats a memory value into a human-readable format.
     *
     * @param  float|int  $bytes  The memory value is specified in bytes.
     *
     * @return string  A formatted string (e.g., "2.00 MB").
     */
    protected function memory(float|int $bytes): string
    {
        return $this->memory->format((int) $bytes);
    }
}
