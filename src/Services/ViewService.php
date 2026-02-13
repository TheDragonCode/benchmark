<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\View\ProgressBarView;
use DragonCode\Benchmark\View\TableView;

use function is_array;
use function is_numeric;
use function round;
use function sprintf;

class ViewService
{
    protected ?int $roundPrecision = null;

    public function __construct(
        protected MemoryService $memory = new MemoryService,
        protected TableView $table = new TableView,
        protected ProgressBarView $progressBar = new ProgressBarView,
    ) {}

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

    public function progressBar(): ProgressBarView
    {
        return $this->progressBar;
    }

    public function emptyLine(int $count = 1): void
    {
        echo "\r";
    }

    protected function appendMs(array $data): array
    {
        foreach ($data as &$values) {
            foreach ($values as $key => &$value) {
                if ($key === '#' || ! is_array($value)) {
                    continue;
                }

                $time = $this->roundTime($value['time']);
                $ram  = $this->roundRam($value['ram'] ?? null);

                $value = ! empty($ram)
                    ? sprintf('%s ms - %s', $time, $ram)
                    : sprintf('%s ms', $time);
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

    protected function roundRam(float|int|null $bytes): ?string
    {
        if ($bytes !== null) {
            return $this->memory->format((int) $bytes);
        }

        return null;
    }
}
