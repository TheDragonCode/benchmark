<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use Closure;
use DragonCode\Support\Facades\Helpers\Arr as DragonArray;

use function array_flip;
use function array_keys;
use function array_map;
use function array_values;
use function is_array;
use function usort;

class ArrService
{
    public function get(array $data, string $key): mixed
    {
        return DragonArray::get($data, $key);
    }

    public function forget(array $data, mixed $key): array
    {
        unset($data[$key]);

        return $data;
    }

    public function map(array $data, Closure $callback): array
    {
        return array_map($callback, array_values($data), array_keys($data));
    }

    public function sort(array $data, string $key = 'time'): array
    {
        usort($data, function (array|float $a, array|float $b) use ($key) {
            $a = is_array($a) ? $this->get($a, $key) : $a;
            $b = is_array($b) ? $this->get($b, $key) : $b;

            if ($a === $b) {
                return 0;
            }

            return $a < $b ? -1 : 1;
        });

        return $data;
    }

    public function pluck(array $data, string $value, ?string $key = null): array
    {
        $result = [];

        $index = 0;

        foreach ($data as $item) {
            $position = $key ? $item[$key] : $index;

            $result[$position] = $item[$value];

            ++$index;
        }

        return $result;
    }

    public function flip(array $data): array
    {
        return array_flip($data);
    }
}
