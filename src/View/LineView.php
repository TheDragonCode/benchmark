<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

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
        for ($i = 0; $i < $count; $i++) {
            $this->writeLine('');
        }
    }
}
