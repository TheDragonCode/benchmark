<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Services;

class Math
{
    public function stats(array $data): array
    {
        $items = [];

        foreach ($data as $name => $iterations) {
            $items[$name]['min'] = min($iterations);
            $items[$name]['max'] = max($iterations);
            $items[$name]['avg'] = array_sum($iterations) / count($iterations);
        }

        return $items;
    }

    public function winnerBy(string $key, array $data): string
    {
        $items = $this->stats($data);

        $winner = '';
        $value  = null;

        foreach ($items as $name => $stats) {
            if (is_null($value)) {
                $value  = $stats[$key];
                $winner = $name;

                continue;
            }

            if ($stats[$key] < $value) {
                $value  = $stats[$key];
                $winner = $name;

                continue;
            }

            if ($stats[$key] === $value) {
                $winner .= ', ' . $name;
            }
        }

        return sprintf('Winner is "%s"', $winner);
    }
}
