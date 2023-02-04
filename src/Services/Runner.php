<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Services;

class Runner
{
    public function call(callable $callback): float
    {
        $startAt = hrtime(true);

        $callback();

        return $this->diff(hrtime(true), $startAt);
    }

    protected function diff(float $now, float $startAt): float
    {
        return ($now - $startAt) / 1e+6;
    }
}
