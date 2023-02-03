<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Transformers;

class Winner extends Base
{
    public function transform(array $data): array
    {
        $values = $data['avg'];

        return $this->winner($values, $this->find($values));
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
