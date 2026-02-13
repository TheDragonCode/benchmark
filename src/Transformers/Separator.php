<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

use Symfony\Component\Console\Helper\TableSeparator;

class Separator extends Base
{
    public function transform(array $data): array
    {
        return [new TableSeparator];
    }
}
