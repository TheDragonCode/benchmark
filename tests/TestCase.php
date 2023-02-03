<?php

declare(strict_types=1);

namespace Tests;

use DragonCode\RuntimeComparison\Comparator;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function comparator(): Comparator
    {
        return new Comparator();
    }

    protected function work(): void
    {
        $string = implode(',', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $array  = explode(',', $string);
        $string = implode(',', $array);
        $array  = explode(',', $string);
    }
}
