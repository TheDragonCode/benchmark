<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\View\ProgressBar;
use DragonCode\Benchmark\View\Table;
use DragonCode\Support\Facades\Helpers\Digit;
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
        foreach ($data as &$values) {
            foreach ($values as $key => &$value) {
                if ($key === '#' || ! is_array($value)) {
                    continue;
                }

                $value = sprintf('%s ms - %sb', $this->roundTime($value['time']), $this->roundRam($value['ram']));
            }
        }

        return $data;
    }

    protected function roundTime(float $value): float
    {
        if (is_numeric($this->roundPrecision)) {
            return round($value, $this->roundPrecision);
        }

        return $value;
    }

    protected function roundRam(float $value): string
    {
        return Digit::toShort($value);
    }
}
