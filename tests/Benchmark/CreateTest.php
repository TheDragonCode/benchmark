<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use DragonCode\Benchmark\Benchmark;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function testAsDynamic(): void
    {
        $this->assertInstanceOf(Benchmark::class, new Benchmark());
    }

    public function testAsStatic(): void
    {
        $this->assertInstanceOf(Benchmark::class, Benchmark::make());
    }
}
