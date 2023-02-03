<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Services;

use DragonCode\RuntimeComparison\View\Info;
use DragonCode\RuntimeComparison\View\Table;
use Symfony\Component\Console\Style\SymfonyStyle;

class View
{
    protected Table $table;

    protected Info $info;

    public function __construct(
        SymfonyStyle $io
    ) {
        $this->table = new Table($io);
        $this->info  = new Info($io);
    }

    public function table(array $data): void
    {
        $this->table->show($data);
    }

    public function info(string $message): void
    {
        $this->info->show($message);
    }
}
