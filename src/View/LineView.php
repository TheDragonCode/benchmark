<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

class LineView extends View
{
    public function newLine(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->writeLine('');
        }
    }

    protected function stream()
    {
        if (static::$stream === null) {
            static::$stream = fopen('php://stdout', 'wb');
        }

        return static::$stream;
    }
}
