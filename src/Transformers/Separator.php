<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Transformers;

use Symfony\Component\Console\Helper\TableSeparator;

class Separator extends Base
{
    public function transform(array $data, ?int $roundPrecision): array
    {
        return [new TableSeparator()];
    }
}
