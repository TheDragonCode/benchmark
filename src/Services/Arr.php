<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Services;

class Arr
{
    public function forget(array $data, mixed $key): array
    {
        unset($data[$key]);

        return $data;
    }

    public function map(array $data, callable $callback): array
    {
        return array_map($callback, array_values($data), array_keys($data));
    }

    public function sort(array $data, ?string $key = null): array
    {
        usort($data, function (array|float $a, array|float $b) use ($key) {
            $a = is_array($a) ? $a[$key] : $a;
            $b = is_array($b) ? $b[$key] : $b;

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
