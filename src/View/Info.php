<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\View;

use Symfony\Component\Console\Style\SymfonyStyle;

class Info
{
    public function __construct(
        protected SymfonyStyle $io
    ) {
    }

    public function show(string $message): void
    {
        $this->io->info($message);
    }
}
