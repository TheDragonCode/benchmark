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

    protected ?int $roundPrecision = null;

    public function __construct(
        protected SymfonyStyle $io
    ) {
        $this->table       = new Table($this->io);
        $this->progressBar = new ProgressBar($this->io);
    }

    public function setRound(?int $precision): void
    {
        $this->roundPrecision = $precision;
    }

    public function table(array $data): void
    {
        $this->table->show(
            $this->appendMs($data)
        );
    }

    public function progressBar(): ProgressBar
    {
        return $this->progressBar;
    }

    public function emptyLine(int $count = 1): void
    {
        $this->io->newLine($count);
    }

    protected function appendMs(array $data): array
    {
        foreach ($data as &$value) {
            if (is_array($value)) {
                $value = $this->appendMs($value);

                continue;
            }

            if (is_numeric($value)) {
                $value = $this->round($value) . ' ms';
            }
        }

        return $data;
    }

    protected function round(float $value): float
    {
        if (is_numeric($this->roundPrecision)) {
            return round($value, $this->roundPrecision);
        }

        return $value;
    }
}
