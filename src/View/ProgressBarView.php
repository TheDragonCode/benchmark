<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use DragonCode\Benchmark\Contracts\ProgressBar;

use function floor;
use function max;
use function str_repeat;
use function vsprintf;

class ProgressBarView extends View implements ProgressBar
{
    protected int $current = 0;

    protected int $width = 28;

    protected int $total = 100;

    /**
     * Creates a progress bar with the specified total number of steps.
     *
     * @param  int  $total  The total number of steps.
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

        $this->write(PHP_EOL);
    }

    /**
     * Renders the current state of the progress bar.
     */
    protected function display(): void
    {
        $percent = $this->percent();

        $filled = $this->filledText($percent);
        $empty  = $this->emptyText($filled);

        $line = $this->buildLine($filled, $empty, $percent);

        $this->write("\r" . $line);
    }

    protected function buildLine(int $filled, int $empty, float $percent): string
    {
        return vsprintf(' %s/%s [%s] %s%%', [
            $this->numberFormat($this->current),
            $this->numberFormat($this->total),
            $this->buildBar($filled, $empty),
            $this->percentText($percent),
        ]);
    }

    protected function buildBar(int $filled, int $empty): string
    {
        return str_repeat('▓', $filled) . str_repeat('░', $empty);
    }

    protected function percent(): float
    {
        return $this->current / $this->total;
    }

    protected function filledText(float $percent): int
    {
        return (int) floor($percent * $this->width);
    }

    protected function emptyText(int $filled): int
    {
        return $this->width - $filled;
    }

    protected function percentText(float $percent): int
    {
        return (int) floor($percent * 100);
    }

    protected function numberFormat(int $number): string
    {
        return number_format($number, thousands_separator: "'");
    }
}
