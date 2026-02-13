<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use DragonCode\Benchmark\Services\CollectorService;

class CollectorFixture extends CollectorService
{
    public function all(): array
    {
        return [
            [
                [4.56789012, 50.67890],
                [5.67890123, 60.78901],
                [6.78901234, 70.89012],
            ],
            [
                [1.23456789, 20.12345],
                [2.34567890, 30.23456],
                [3.45678901, 40.34567],
            ],
        ];
    }
}
