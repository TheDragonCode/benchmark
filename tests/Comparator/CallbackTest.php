<?php

declare(strict_types=1);

namespace Tests\Comparator;

use Tests\TestCase;

class CallbackTest extends TestCase
{
    public function testAsProperties(): void
    {
        $this->comparator()->compare(
            fn () => usleep(50),
            fn () => usleep(50),
        );

        $this->assertTrue(true);
    }

    public function testAsArray(): void
    {
        $this->comparator()->compare([
            fn () => usleep(50),
            fn () => usleep(50),
        ]);

        $this->assertTrue(true);
    }
}
