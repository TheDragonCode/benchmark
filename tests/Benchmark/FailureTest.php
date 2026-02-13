<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use DragonCode\Benchmark\Exceptions\ValueIsNotCallableException;
use Tests\TestCase;
use TypeError;

class FailureTest extends TestCase
{
    public function testAsProperties(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('must be of type Closure|array, int given');

        $this->benchmark()->compare(123);
    }

    public function testAsArray(): void
    {
        $this->expectException(ValueIsNotCallableException::class);
        $this->expectExceptionMessage('The array value must be of type callable, integer given.');

        $this->benchmark()->compare([
            'first'  => 123,
            'second' => 123,
        ]);
    }

    public function testAsPropertiesWithIterations(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('must be of type Closure|array, int given');

        $this->benchmark()->iterations(5)->compare(123);
    }

    public function testAsArrayWithIterations(): void
    {
        $this->expectException(ValueIsNotCallableException::class);
        $this->expectExceptionMessage('The array value must be of type callable, integer given.');

        $this->benchmark()->iterations(5)->compare([
            'first'  => 123,
            'second' => 123,
        ]);
    }

    public function testAsPropertiesWithoutData(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('must be of type Closure|array, int given');

        $this->benchmark()->compare(123);
    }

    public function testAsArrayWithoutData(): void
    {
        $this->expectException(ValueIsNotCallableException::class);
        $this->expectExceptionMessage('The array value must be of type callable, integer given.');

        $this->benchmark()->compare([
            'first'  => 123,
            'second' => 123,
        ]);
    }
}
