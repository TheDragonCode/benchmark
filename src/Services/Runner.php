<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Services;

class Runner
{
    protected int $precision = 3;

    public function call(callable $callback): float
    {
        $startAt = $this->time();

        $callback();

        return $this->diff($startAt);
    }

    protected function diff(float $startAt): float
    {
        return round($this->time() - $startAt, $this->precision);
    }

    protected function time(): float
    {
        return microtime(true);
    }
}
