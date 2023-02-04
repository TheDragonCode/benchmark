<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Contracts;

interface Transformer
{
    public function transform(array $data): array;
}
