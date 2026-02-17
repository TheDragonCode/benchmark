<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use DragonCode\Benchmark\Contracts\ProgressBar;

class SilentProgressBarView extends View implements ProgressBar
{
    public function create(int $total): static
    {
        return $this;
    }

    public function advance(int $step = 1): void {}

    public function finish(): void {}
}
