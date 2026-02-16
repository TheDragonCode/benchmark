<?php

declare(strict_types=1);

use DragonCode\Benchmark\Benchmark;
use DragonCode\Benchmark\Services\CollectorService;
use Tests\Fixtures\CollectorFixture;

function benchmark(bool|CollectorService $collector = true): Benchmark
{
    $instance = match (true) {
        $collector === true                    => new CollectorFixture,
        $collector instanceof CollectorService => $collector,
        default                                => new CollectorService,
    };

    return (new Benchmark(
        collector: $instance
    ))->iterations(3);
}
