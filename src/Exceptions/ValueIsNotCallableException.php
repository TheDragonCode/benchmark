<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Exceptions;

use TypeError;

use function gettype;

class ValueIsNotCallableException extends TypeError
{
    /**
     * Creates an exception indicating that the provided value does not match the Closure type.
     *
     * @param  mixed  $value  The value that does not match the Closure type.
     */
    public function __construct(mixed $value)
    {
        parent::__construct(sprintf('The property value must be of type Closure, %s given.', gettype($value)));
    }
}
