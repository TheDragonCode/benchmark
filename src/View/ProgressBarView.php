<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use function floor;
use function max;
use function str_repeat;

class ProgressBarView extends View
{
    protected int $current = 0;

    protected int $barWidth = 28;

    protected int $total = 100;

    public function create(int $total): static
    {
        $this->total = max(1, $total);

        $this->display();

        return $this;
    }

    public function advance(int $step = 1): void
    {
        $this->current += $step;

        $this->display();
    }

    public function finish(): void
    {
        $this->current = $this->total;

        $this->display();

        $this->write(PHP_EOL);
    }

    protected function display(): void
    {
        $percent  = $this->current / $this->total;
        $filled   = (int) floor($percent * $this->barWidth);
        $empty    = $this->barWidth - $filled;
        $percText = (int) floor($percent * 100);

        $bar = str_repeat('▓', $filled) . str_repeat('░', $empty);

        $line = " {$this->current}/{$this->total} [{$bar}] {$percText}%";

        $this->write("\r" . $line);
    }

    /**
     * @return resource
     */
    protected function stream()
    {
        if (static::$stream === null) {
            static::$stream = fopen('php://stderr', 'w');
        }

        return static::$stream;
    }
}
