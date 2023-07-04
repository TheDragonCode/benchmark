<?php

declare(strict_types=1);

namespace Tests;

use DragonCode\Benchmark\Benchmark;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function benchmark(): Benchmark
    {
        return new Benchmark();
    }

    protected function work(): void
    {
        usleep(10);
    }
}
