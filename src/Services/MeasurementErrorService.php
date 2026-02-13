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

    public function filter(array $data): array
    {
        $count = $this->count($data);

        if ($this->disabled($count)) {
            return $data;
        }

        return $this->partial($data, $count);
    }

    protected function partial(array $data, int $count): array
    {
        return array_slice(
            array        : $this->sort($data),
            offset       : $this->offset($count),
            length       : $this->take($count),
            preserve_keys: true
        );
    }

    protected function offset(int $count): int
    {
        return (int) ($count * $this->percent);
    }

    protected function take(int $count): int
    {
        return $count - (2 * $this->offset($count));
    }

    protected function count(array $values): int
    {
        return count($values);
    }

    protected function disabled(int $count): bool
    {
        return $count < $this->minCount;
    }

    protected function sort(array $values): array
    {
        asort($values, SORT_NUMERIC);

        return $values;
    }
}
