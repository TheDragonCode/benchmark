<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use Symfony\Component\Console\Helper\ProgressBar as ProgressBarService;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProgressBar
{
    public function __construct(
        protected SymfonyStyle $io
    ) {
    }

    public function create(int $count): ProgressBarService
    {
        return $this->io->createProgressBar($count);
    }
}
