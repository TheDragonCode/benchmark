<?php

declare(strict_types=1);

namespace Tests\Comparator;

use DragonCode\RuntimeComparison\Exceptions\ValueIsNotCallableException;
use Tests\TestCase;
use TypeError;

class FailureTest extends TestCase
{
    public function testAsProperties(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('must be of type callable|array, int given');

        $this->comparator()->compare(123);
    }

    public function testAsArray(): void
    {
        $this->expectException(ValueIsNotCallableException::class);
        $this->expectExceptionMessage('The array value must be of type callable, integer given.');

        $this->comparator()->compare([
            'first'  => 123,
            'second' => 123,
        ]);
    }
}
