<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Transformers;

class Stats extends Base
{
    protected array $methods = [
        'min',
        'max',
        'avg',
    ];

    public function transform(array $data, ?int $roundPrecision): array
    {
        return $this->calculate($data, $roundPrecision);
    }

    protected function calculate(array $data, ?int $roundPrecision): array
    {
        $items = [];

        foreach ($data as $name => $iterations) {
            foreach ($this->methods as $method) {
                $this->put($items, $method, $name, fn () => $this->round(call_user_func([$this, $method], $iterations), $roundPrecision));
            }
        }

        return $items;
    }

    protected function min(array $values): float
    {
        return min($values);
    }

    protected function max(array $values): float
    {
        return max($values);
    }

    protected function avg(array $values): float
    {
        return array_sum($values) / count($values);
    }
}
