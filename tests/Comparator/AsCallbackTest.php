<?php

declare(strict_types=1);

namespace Tests\Comparator;

use Tests\TestCase;

class AsCallbackTest extends TestCase
{
    public function testDefault(): void
    {
        $this->comparator()->compare(
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->assertTrue(true);
    }

    public function testIterations(): void
    {
        $this->comparator()->iterations(5)->compare(
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->comparator()->iterations(500)->withoutData()->compare(
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->assertTrue(true);
    }

    public function testWithoutData(): void
    {
        $this->comparator()->withoutData()->compare(
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->assertTrue(true);
    }

    public function testRound(): void
    {
        $this->comparator()->round(2)->compare(
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->assertTrue(true);
    }
}
