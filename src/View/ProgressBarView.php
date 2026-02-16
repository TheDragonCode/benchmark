<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use function floor;
use function max;
use function str_repeat;

class ProgressBarView extends View
{
    protected bool $enabled = true;

    protected int $current = 0;

    protected int $barWidth = 28;

    protected int $total = 100;

    /**
     * Disables the progress bar display.
     *
     * @return $this
     */
    public function disable(): static
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * Creates a progress bar with the specified total number of steps.
     *
     * @param  int  $total  The total number of steps.
     *
     * @return $this
     */
    public function create(int $total): static
    {
        $this->total = max(1, $total);

        $this->display();

        return $this;
    }

    /**
     * Advances the progress bar by the specified number of steps.
     *
     * @param  int  $step  The number of steps to advance.
     */
    public function advance(int $step = 1): void
    {
        $this->current += $step;

        $this->display();
    }

    /**
     * Finishes the progress bar by setting the current value equal to the total.
     */
    public function finish(): void
    {
        $this->current = $this->total;

        $this->display();

        if ($this->enabled) {
            $this->write(PHP_EOL);
        }
    }

    /**
     * Renders the current state of the progress bar.
     */
    protected function display(): void
    {
        if (! $this->enabled) {
            return;
        }

        $percent  = $this->current / $this->total;
        $filled   = (int) floor($percent * $this->barWidth);
        $empty    = $this->barWidth - $filled;
        $percText = (int) floor($percent * 100);

        $bar = str_repeat('▓', $filled) . str_repeat('░', $empty);

        $line = " {$this->current}/{$this->total} [{$bar}] {$percText}%";

        $this->write("\r" . $line);
    }
}
