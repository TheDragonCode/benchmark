<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\View;

use Symfony\Component\Console\Style\SymfonyStyle;

class Table
{
    public function __construct(
        protected SymfonyStyle $io
    ) {
    }

    public function show(array $data): void
    {
        $this->io->table($this->headers($data), $data);
    }

    protected function headers(array $data): array
    {
        return array_keys(array_values($data)[0]);
    }
}
