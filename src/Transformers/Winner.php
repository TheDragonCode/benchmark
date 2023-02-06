<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

use DragonCode\Benchmark\Services\Arr;

class Winner extends Base
{
    protected string $key = 'avg';

    protected array $orders = [
        1 => '<fg=green>- %d -</>',
        2 => '<fg=yellow>- %d -</>',
        3 => '<fg=blue>- %d -</>',
    ];

    public function __construct(
        protected Arr $arr = new Arr()
    ) {
    }

    public function transform(array $data): array
    {
        $values = $data[$this->key];

        $names = $this->prepare($values);

        return $this->order($values, $names);
    }

    protected function order(array $data, array $names): array
    {
        $items = [];

        foreach (array_keys($data) as $key) {
            if ($key === '#') {
                continue;
            }

            $position = $names[$key] + 1;

            $this->put($items, 'Order', $key, fn () => $this->color($position));
        }

        return $items;
    }

    protected function prepare(array $data): array
    {
        $data = $this->arr->forget($data, '#');
        $data = $this->arr->map($data, fn (float $time, mixed $name) => compact('name', 'time'));
        $data = $this->arr->sort($data, 'time');
        $data = $this->arr->pluck($data, 'name');

        return $this->arr->flip($data);
    }

    protected function color(int $order): string
    {
        if ($template = $this->orders[$order] ?? null) {
            return sprintf($template, $order);
        }

        return sprintf('- %d -', $order);
    }
}
