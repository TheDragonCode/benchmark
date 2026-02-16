<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

class LineView extends View
{
    public function line(string $text): void
    {
        $this->writeLine($text);
    }

    public function newLine(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->writeLine('');
        }
    }
}
