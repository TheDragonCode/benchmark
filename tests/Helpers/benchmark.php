<?php

declare(strict_types=1);

use DragonCode\Benchmark\Benchmark;

function benchmark(): Benchmark
{
    return (new Benchmark)->iterations(3);
}
