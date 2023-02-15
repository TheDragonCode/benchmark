<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use Tests\TestCase;

class HardTest extends TestCase
{
    protected string $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras efficitur nisi in scelerisque ultricies.';

    protected int $count = 100000;

    public function testMemory(): void
    {
        $this->benchmark()->iterations(10)->compare(
            fn () => $this->process(),
            fn () => $this->process()
        );

        $this->assertTrue(true);
    }

    protected function process(): array
    {
        $result = [];

        for ($i = 0; $i < $this->count; ++$i) {
            $result[] = $this->lorem;
        }

        return $result;
    }
}
