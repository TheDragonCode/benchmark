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
        $this->io->table(array_keys($data[0]), $data);
    }
}
