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

    public function round(?int $precision): void
    {
        $this->precision = $precision;
    }

    /**
     * @param  \DragonCode\Benchmark\Data\ResultData[]  $collection
     */
    public function toTable(array $collection): array
    {
        $table = $this->map($this->table, $collection);

        return $this->order($table, $collection);
    }

    /**
     * @param  \DragonCode\Benchmark\Data\ResultData[]  $collection
     */
    protected function map(array $table, array $collection): array
    {
        foreach ($collection as $key => $item) {
            $table[0][$key] = $this->value($item->min);
            $table[1][$key] = $this->value($item->max);
            $table[2][$key] = $this->value($item->avg);
            $table[3][$key] = $this->value($item->total);
            $table[5][$key] = 0;
        }

        return $table;
    }

    /**
     * @param  \DragonCode\Benchmark\Data\ResultData[]  $collection
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

    protected function value(MetricData $metric): string
    {
        $time   = $this->time($metric->time);
        $memory = $this->memory($metric->memory);

        return $memory
            ? sprintf('%s ms - %s', $time, $memory)
            : sprintf('%s ms', $time);
    }

    protected function time(float $value): float
    {
        if ($this->precision === null) {
            return $value;
        }

        return round($value, $this->precision);
    }

    protected function memory(float|int $bytes): string
    {
        return $this->memory->format((int) $bytes);
    }
}
