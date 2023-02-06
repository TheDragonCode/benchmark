<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Contracts;

interface Transformer
{
    public function transform(array $data): array;
}
