<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

class SeparatorTransformer extends Transformer
{
    public function transform(array $data): array
    {
        return [new TableSeparator];
    }
}
