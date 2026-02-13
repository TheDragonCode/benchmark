<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Exceptions;

use TypeError;

use function gettype;

class ValueIsNotCallableException extends TypeError
{
    public function __construct(mixed $value)
    {
        parent::__construct(sprintf('The array value must be of type Closure, %s given.', gettype($value)), 500);
    }
}
