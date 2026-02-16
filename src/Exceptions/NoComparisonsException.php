<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Exceptions;

use RuntimeException;

class NoComparisonsException extends RuntimeException
{
    /**
     * Creates an exception indicating that the "compare" method was not called.
     */
    public function __construct()
    {
        parent::__construct('Method "compare" was not called. No comparisons were made.');
    }
}
