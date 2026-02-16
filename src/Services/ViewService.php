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

    public function table(array $data): void
    {
        $this->table->show($data);
    }

    public function progressBar(): ProgressBarView
    {
        return $this->progressBar;
    }

    public function line(string $text): void
    {
        $this->line->line($text);
    }

    public function emptyLine(int $count = 1): void
    {
        $this->line->newLine($count);
    }
}
