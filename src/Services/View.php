<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Services;

use DragonCode\RuntimeComparison\View\ProgressBar;
use DragonCode\RuntimeComparison\View\Table;
use Symfony\Component\Console\Style\SymfonyStyle;

class View
{
    protected Table $table;

    protected ProgressBar $progressBar;

    public function __construct(
        protected SymfonyStyle $io
    ) {
        $this->table       = new Table($this->io);
        $this->progressBar = new ProgressBar($this->io);
    }

    public function table(array $data): void
    {
        $this->table->show($data);
    }

    public function progressBar(): ProgressBar
    {
        return $this->progressBar;
    }

    public function emptyLine(int $count = 1): void
    {
        $this->io->newLine($count);
    }
}
