<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\View;

use Symfony\Component\Console\Helper\Table as TableHelper;
use Symfony\Component\Console\Output\OutputInterface;

class Table
{
    protected TableHelper $table;

    public function __construct(
        OutputInterface $output
    ) {
        $this->table = new TableHelper($output);
    }

    public function show(array $data): void
    {
        $this->table
            ->setHeaders(array_keys($data))
            ->setRows($data)
            ->render();
    }
}
