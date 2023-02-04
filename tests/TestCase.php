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
        random_int(0, PHP_INT_MAX);
    }
}
