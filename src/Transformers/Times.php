<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Transformers;

class Times extends Base
{
    public function transform(array $data, ?int $roundPrecision): array
    {
        $items = [];

        foreach ($data as $name => $values) {
            foreach ($values as $iteration => $time) {
                $items[$iteration]['#']   = $iteration;
                $items[$iteration][$name] = $this->round($time, $roundPrecision);
            }
        }

        return array_values($items);
    }
}
