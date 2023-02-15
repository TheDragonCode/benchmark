<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use Tests\TestCase;

class CoverageTest extends TestCase
{
    public function testDefault(): void
    {
        $this->benchmark()->iterations(2)->compare(
            fn () => $this->work(),
            fn () => $this->work(),
            fn () => $this->work(),
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->assertTrue(true);
    }
}
