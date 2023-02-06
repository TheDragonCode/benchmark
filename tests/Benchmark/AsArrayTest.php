<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use Tests\TestCase;

class AsArrayTest extends TestCase
{
    public function testDefault(): void
    {
        $this->benchmark()->compare([
            'foo' => fn () => $this->work(),
            'bar' => fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }

    public function testIterations(): void
    {
        $this->benchmark()->iterations(5)->compare([
            'foo' => fn () => $this->work(),
            'bar' => fn () => $this->work(),
        ]);

        $this->benchmark()->iterations(500)->withoutData()->compare([
            'foo' => fn () => $this->work(),
            'bar' => fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }

    public function testWithoutData(): void
    {
        $this->benchmark()->withoutData()->compare([
            'foo' => fn () => $this->work(),
            'bar' => fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }

    public function testRound(): void
    {
        $this->benchmark()->round(2)->compare([
            'foo' => fn () => $this->work(),
            'bar' => fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }
}
