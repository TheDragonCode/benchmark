<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

class Times extends Base
{
    public function transform(array $data): array
    {
        $items = [];

        foreach ($data as $name => $values) {
            foreach ($values as $iteration => $time) {
                $items[$iteration]['#']   = $iteration;
                $items[$iteration][$name] = $time;
            }
        }

        return array_values($items);
    }
}
