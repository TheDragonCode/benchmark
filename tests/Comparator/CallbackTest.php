<?php

declare(strict_types=1);

namespace Tests\Comparator;

use Tests\TestCase;

class CallbackTest extends TestCase
{
    public function testAsProperties(): void
    {
        $this->comparator()->compare(
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->assertTrue(true);
    }

    public function testAsArray(): void
    {
        $this->comparator()->compare([
            fn () => $this->work(),
            fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }

    public function testAsPropertiesWithIterations(): void
    {
        $this->comparator()->iterations(5)->compare(
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->assertTrue(true);
    }

    public function testAsArrayWithIterations(): void
    {
        $this->comparator()->iterations(5)->compare([
            fn () => $this->work(),
            fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }
}
