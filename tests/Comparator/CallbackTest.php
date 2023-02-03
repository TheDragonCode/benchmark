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

    public function testAsPropertiesWithoutData(): void
    {
        $this->comparator()->withoutData()->compare(
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->assertTrue(true);
    }

    public function testAsArrayWithoutData(): void
    {
        $this->comparator()->withoutData()->compare([
            fn () => $this->work(),
            fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }

    public function testAsPropertiesRound(): void
    {
        $this->comparator()->roundPrecision(4)->compare(
            fn () => $this->work(),
            fn () => $this->work(),
        );

        $this->assertTrue(true);
    }

    public function testAsArrayRound(): void
    {
        $this->comparator()->roundPrecision(4)->compare([
            fn () => $this->work(),
            fn () => $this->work(),
        ]);

        $this->assertTrue(true);
    }
}
