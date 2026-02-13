<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

class LineView extends View
{
    protected string $streamName = 'php://stdout';

    public function newLine(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->writeLine('');
        }
    }
}
