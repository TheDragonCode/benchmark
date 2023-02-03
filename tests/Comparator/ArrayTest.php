<?php

declare(strict_types=1);

namespace Tests\Comparator;

use Tests\TestCase;

class ArrayTest extends TestCase
{
    public function testAsArray(): void
    {
        $this->comparator()->compare([
            'first'  => fn () => $this->work(),
            'second' => fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }

    public function testAsArrayWithIterations(): void
    {
        $this->comparator()->iterations(5)->compare([
            'first'  => fn () => $this->work(),
            'second' => fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }
}
