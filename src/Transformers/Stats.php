<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

use DragonCode\Benchmark\Services\MeasurementError;

class Stats extends Base
{
    protected array $methods = [
        'min',
        'max',
        'avg',
    ];

    public function __construct(
        protected MeasurementError $measurementError = new MeasurementError()
    ) {
    }

    public function transform(array $data): array
    {
        return $this->calculate($data);
    }

    protected function calculate(array $data): array
    {
        $items = [];

        foreach ($data['each'] as $name => $iterations) {
            foreach ($this->methods as $method) {
                $this->put($items, $method, $name, fn () => call_user_func([$this, $method], $iterations));
            }
        }

        foreach ($data['total'] as $name => $time) {
            $this->put($items, 'total', $name, fn () => $time);
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
        $values = $this->filter($values);

        return array_sum($values) / count($values);
    }

    protected function filter(array $values): array
    {
        return $this->measurementError->filter($values);
    }
}
