<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Services;

class Runner
{
    public function call(callable $callback): float
    {
        $startAt = $this->time();

        $callback();

        return $this->diff($startAt);
    }

    protected function diff(float $startAt): float
    {
        return $this->time() - $startAt;
    }

    protected function time(): float
    {
        return microtime(true);
    }
}
