<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\View\LineView;
use DragonCode\Benchmark\View\ProgressBarView;
use DragonCode\Benchmark\View\TableView;

class ViewService
{
    public function __construct(
        protected TableView $table = new TableView,
        protected ProgressBarView $progressBar = new ProgressBarView,
        protected LineView $line = new LineView,
    ) {}

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
    public function progressBar(): ProgressBarView
    {
        return $this->progressBar;
    }

    /**
     * Outputs a line of text.
     *
     * @param  string  $text  The text to output.
     */
    public function line(string $text): void
    {
        $this->line->line($text);
    }

    /**
     * Outputs empty lines.
     *
     * @param  int  $count  The number of empty lines.
     */
    public function emptyLine(int $count = 1): void
    {
        $this->line->newLine($count);
    }
}
