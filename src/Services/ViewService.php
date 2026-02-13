<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\View\ProgressBarView;
use DragonCode\Benchmark\View\TableView;

class ViewService
{
    public function __construct(
        protected TableView $table = new TableView,
        protected ProgressBarView $progressBar = new ProgressBarView,
    ) {}

    public function table(array $data): void
    {
        $this->table->show($data);
    }

    public function progressBar(): ProgressBarView
    {
        return $this->progressBar;
    }

    public function emptyLine(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            echo PHP_EOL;
        }
    }
}
