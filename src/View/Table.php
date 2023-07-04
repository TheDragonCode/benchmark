<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use Symfony\Component\Console\Style\SymfonyStyle;

use function array_keys;
use function array_values;

class Table
{
    public function __construct(
        protected SymfonyStyle $io
    ) {}

    public function show(array $data): void
    {
        $this->io->table($this->headers($data), $data);
    }

    protected function headers(array $data): array
    {
        return array_keys(array_values($data)[0]);
    }
}
