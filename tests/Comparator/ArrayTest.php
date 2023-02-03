<?php

declare(strict_types=1);

namespace Tests\Comparator;

use Tests\TestCase;

class ArrayTest extends TestCase
{
    public function testAsArray(): void
    {
        $this->comparator()->compare([
            'first'  => fn () => usleep(50),
            'second' => fn () => usleep(50),
        ]);

        $this->assertTrue(true);
    }
}
