<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Transformers;

use DragonCode\RuntimeComparison\Contracts\Transformer as TransformerContract;

abstract class Base implements TransformerContract
{
    protected function put(array &$items, string $key, mixed $name, callable $callback): void
    {
        $items[$key]['#']   = $key;
        $items[$key][$name] = $callback();
    }

    protected function round(float $value, ?int $precision): float
    {
        return is_numeric($precision) ? round($value, $precision) : $value;
    }
}
