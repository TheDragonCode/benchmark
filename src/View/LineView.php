<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use function array_fill;
use function implode;
use function max;

class LineView extends View
{
    /**
     * Outputs a line of text.
     *
     * @param  string  $text  The text to output.
     */
    public function line(string $text): void
    {
        $this->writeLine($text);
    }

    /**
     * Outputs the specified number of empty lines.
     *
     * @param  int  $count  The number of empty lines.
     */
    public function newLine(int $count = 1): void
    {
        $this->writeLine(
            $this->buildLine(max(1, $count))
        );
    }

    protected function buildLine(int $count): string
    {
        return implode('', array_fill(0, max(1, $count), PHP_EOL));
    }
}
