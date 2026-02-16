<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use function array_slice;
use function asort;
use function count;

class MeasurementErrorService
{
    protected float $percent = 0.1;

    protected int $minCount = 10;

    /**
     * Filters data by discarding extreme values to reduce measurement error.
     *
     * @param  array  $data  An array of numeric measurement values.
     *
     * @return array  The filtered array of values.
     */
    public function filter(array $data): array
    {
        $count = count($data);

        if ($this->disabled($count)) {
            return $data;
        }

        return $this->partial($data, $count);
    }

    /**
     * Returns the central part of a sorted array, discarding extreme values.
     *
     * @param  array  $data  An array of numeric values.
     * @param  int  $count  The number of elements in the array.
     *
     * @return array
     */
    protected function partial(array $data, int $count): array
    {
        return array_slice(
            array        : $this->sort($data),
            offset       : $this->offset($count),
            length       : $this->take($count),
            preserve_keys: true
        );
    }

    /**
     * Calculates the offset for discarding extreme values.
     *
     * @param  int  $count  The number of elements in the array.
     *
     * @return int
     */
    protected function offset(int $count): int
    {
        return (int) ($count * $this->percent);
    }

    /**
     * Calculates the number of elements to keep after filtering.
     *
     * @param  int  $count  The number of elements in the array.
     *
     * @return int
     */
    protected function take(int $count): int
    {
        return $count - (2 * $this->offset($count));
    }

    /**
     * Checks whether filtering is disabled due to insufficient data.
     *
     * @param  int  $count  The number of elements in the array.
     *
     * @return bool
     */
    protected function disabled(int $count): bool
    {
        return $count < $this->minCount;
    }

    /**
     * Sorts the array of values in ascending order.
     *
     * @param  array  $values  An array of numeric values.
     *
     * @return array
     */
    protected function sort(array $values): array
    {
        asort($values, SORT_NUMERIC);

        return $values;
    }
}
