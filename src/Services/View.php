<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Services;

use DragonCode\RuntimeComparison\View\Table;
use Symfony\Component\Console\Style\SymfonyStyle;

class View
{
    protected Table $table;

    public function __construct(
        SymfonyStyle $io
    ) {
        $this->table = new Table($io);
    }

    public function table(array $data): void
    {
        $this->table->show($data);
    }
}
