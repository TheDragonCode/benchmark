<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

use DragonCode\Benchmark\Contracts\Transformer as TransformerContract;

abstract class Base implements TransformerContract
{
    protected function put(array &$items, string $key, mixed $name, callable $callback): void
    {
        $items[$key]['#']   = $key;
        $items[$key][$name] = $callback();
    }
}
