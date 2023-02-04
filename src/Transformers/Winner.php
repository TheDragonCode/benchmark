<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Transformers;

class Winner extends Base
{
    protected array $order = ['avg', 'max', 'min'];

    public function transform(array $data): array
    {
        foreach ($this->order as $key) {
            if ($winner = $this->detectWinner($data[$key])) {
                return $winner;
            }
        }

        return $this->detectWinner($data['avg'], true);
    }

    protected function detectWinner(array $values, bool $force = false): ?array
    {
        $names = $this->find($values);

        if ($force || count($names) !== count($values) - 1) {
            return $this->winner($values, $names);
        }

        return null;
    }

    protected function winner(array $data, array $names): array
    {
        $items = [];

        foreach (array_keys($data) as $key) {
            if ($key === '#') {
                continue;
            }

            in_array($key, $names, true)
                ? $this->put($items, '', $key, fn () => '<fg=green>winner</>')
                : $this->put($items, '', $key, fn () => '<fg=yellow>loser</>');
        }

        return $items;
    }

    protected function find(array $data): array
    {
        $value = null;
        $name  = [];

        foreach ($data as $key => $time) {
            if ($key === '#') {
                continue;
            }

            if (is_null($value) || $time < $value) {
                $value = $time;
                $name  = [$key];

                continue;
            }

            if ($time === $value) {
                $name[] = $key;
            }
        }

        return array_unique($name);
    }
}
