<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\Contracts\ProgressBar;
use DragonCode\Benchmark\View\LineView;
use DragonCode\Benchmark\View\ProgressBarView;
use DragonCode\Benchmark\View\SilentProgressBarView;
use DragonCode\Benchmark\View\TableView;

class ViewService
{
    protected bool $enabled = true;

    public function __construct(
        protected TableView $table = new TableView,
        protected LineView $line = new LineView,
        protected ProgressBarView $progressBar = new ProgressBarView,
        protected SilentProgressBarView $silentProgressBar = new SilentProgressBarView,
    ) {}

    /**
     * @return $this
     */
    public function disable(): static
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * Displays a table with benchmark results.
     *
     * @param  array  $data  Data to display in the table.
     */
    public function table(array $data): void
    {
        $this->table->show($data);
    }

    /**
     * Returns the progress bar instance.
     */
    public function progressBar(): ProgressBar
    {
        return $this->enabled
            ? $this->progressBar
            : $this->silentProgressBar;
    }

    /**
     * Outputs a line of text.
     *
     * @param  string  $text  The text to output.
     */
    public function line(string $text): void
    {
        if ($this->enabled) {
            $this->line->line($text);
        }
    }

    /**
     * Outputs empty lines.
     *
     * @param  int  $count  The number of empty lines.
     */
    public function emptyLine(int $count = 1): void
    {
        if ($this->enabled) {
            $this->line->newLine($count);
        }
    }
}
