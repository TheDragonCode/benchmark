<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

use DragonCode\Benchmark\Services\Arr;
use DragonCode\Benchmark\Services\MeasurementError;

use function array_sum;
use function call_user_func;
use function count;
use function max;
use function min;

class Stats extends Base
{
    protected array $methods = [
        'min',
        'max',
        'avg',
    ];

    public function __construct(
        protected MeasurementError $measurementError = new MeasurementError,
        protected Arr $arr = new Arr
    ) {}

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

        foreach ($data['total'] as $name => $value) {
            $this->put($items, 'total', $name, fn () => ['time' => $value[0]]);
        }

        return $items;
    }

    protected function min(array $values): array
    {
        return [
            'time' => min($this->arr->pluck($values, 'time')),
            'ram'  => min($this->arr->pluck($values, 'ram')),
        ];
    }

    protected function max(array $values): array
    {
        return [
            'time' => max($this->arr->pluck($values, 'time')),
            'ram'  => max($this->arr->pluck($values, 'ram')),
        ];
    }

    protected function avg(array $values): array
    {
        $values = $this->filter($values);

        return [
            'time' => array_sum($this->arr->pluck($values, 'time')) / count($values),
            'ram'  => array_sum($this->arr->pluck($values, 'ram')) / count($values),
        ];
    }

    protected function filter(array $values): array
    {
        return $this->measurementError->filter($values);
    }
}
