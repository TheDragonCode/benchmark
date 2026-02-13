<?php

declare(strict_types=1);

arch()
    ->expect('DragonCode\Benchmark\Exceptions')
    ->toBeClasses();

arch()
    ->expect('DragonCode\Benchmark\Exceptions')
    ->toHaveSuffix('Exception');
