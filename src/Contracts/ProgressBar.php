<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Contracts;

interface ProgressBar
{
    public function create(int $total): static;

    public function advance(int $step = 1): void;

    public function finish(): void;
}
