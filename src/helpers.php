<?php

declare(strict_types=1);

namespace DragonCode\Benchmark;

if (! function_exists('\DragonCode\Benchmark\bench')) {
    function bench(): Benchmark
    {
        return new Benchmark;
    }
}
