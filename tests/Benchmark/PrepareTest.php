<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use Tests\TestCase;

class PrepareTest extends TestCase
{
    public function testCallback(): void
    {
        $result = [];

        $this->benchmark()
            ->iterations(3)
            ->withoutData()
            ->prepare(function () use (&$result) {
                $result[] = 1;
            })
            ->compare([
                'foo' => fn () => $this->work(),
                'bar' => fn () => $this->work(),
            ]);

        $this->assertSame(6, count($result));
    }

    public function testParameters(): void
    {
        $result = [];

        $this->benchmark()
            ->iterations(3)
            ->withoutData()
            ->prepare(function (mixed $name, int $iteration) use (&$result) {
                $result[] = sprintf('%s:%d', $name, $iteration);
            })
            ->compare([
                'foo' => fn () => $this->work(),
                'bar' => fn () => $this->work(),
            ]);

        $this->assertSame(6, count($result));

        $this->assertSame([
            'foo:1',
            'foo:2',
            'foo:3',
            'bar:1',
            'bar:2',
            'bar:3',
        ], $result);
    }

    public function testName(): void
    {
        $result = [];

        $this->benchmark()
            ->iterations(3)
            ->withoutData()
            ->prepare(function (mixed $name) use (&$result) {
                $result[] = $name;
            })
            ->compare([
                'foo' => fn () => $this->work(),
                'bar' => fn () => $this->work(),
            ]);

        $this->assertSame(6, count($result));

        $this->assertSame([
            'foo',
            'foo',
            'foo',
            'bar',
            'bar',
            'bar',
        ], $result);
    }
}
