<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\View;

use Symfony\Component\Console\Output\OutputInterface;

class Info
{
    public function __construct(
        protected OutputInterface $output
    ) {
    }

    public function show(string $message): void
    {
        $this->output->writeln($message);
    }

    public function emptyLine(): void
    {
        $this->output->writeln('');
    }
}
