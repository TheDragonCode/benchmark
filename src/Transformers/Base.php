<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Transformers;

use DragonCode\RuntimeComparison\Contracts\Transformer as TransformerContract;
use DragonCode\Support\Facades\Helpers\Str;

abstract class Base implements TransformerContract
{
    protected function put(array &$items, string $key, mixed $name, callable $callback): void
    {
        $items[$key]['#']   = $key;
        $items[$key][$name] = $callback();
    }

    protected function strPad(int $length): string
    {
        return str_pad('', $length, '-');
    }

    protected function strLength(mixed $value): int
    {
        return Str::length((string) $value);
    }
}
