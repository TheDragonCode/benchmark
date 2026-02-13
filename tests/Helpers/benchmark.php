<?php

declare(strict_types=1);

use DragonCode\Benchmark\Benchmark;
use DragonCode\Benchmark\Services\CollectorService;
use Tests\Fixtures\CollectorFixture;

function benchmark(bool $customCollector = true): Benchmark
{
    $collector = $customCollector ? new CollectorFixture : new CollectorService;

    return (new Benchmark(collector: $collector))->iterations(3);
}
