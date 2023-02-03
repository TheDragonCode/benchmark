<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Services;

use DragonCode\RuntimeComparison\View\Info;
use DragonCode\RuntimeComparison\View\Table;
use Symfony\Component\Console\Output\OutputInterface;

class View
{
    protected Table $table;

    protected Info $info;

    public function __construct(
        OutputInterface $output
    ) {
        $this->table = new Table($output);
        $this->info  = new Info($output);
    }

    public function table(array $data): void
    {
        $this->table->show($data);
    }

    public function stats(array $data): void
    {
        $this->table->show($data);
    }

    public function emptyLine(): void
    {
        $this->info->emptyLine();
    }

    public function info(string $message): void
    {
        $this->info->show($message);
    }
}
