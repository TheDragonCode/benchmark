<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Exceptions;

use ValueError;

use function implode;

class DeviationsNotCalculatedException extends ValueError
{
    /**
     * Creates an exception indicating that deviations were not calculated for the specified callback name.
     *
     * @param  int|string  $name  The name of the callback for which deviation data is missing.
     */
    public function __construct(int|string $name)
    {
        parent::__construct(
            implode(' ', [
                "No information is available for the deviation values for \"$name\".",
                'You must call the "deviations" method before this check.',
            ])
        );
    }
}
