<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison\Exceptions;

use TypeError;

class ValueIsNotCallableException extends TypeError
{
    public function __construct(string $actualType)
    {
        parent::__construct(sprintf('The array value must be of type callable, %s given.', $actualType), 500);
    }
}
