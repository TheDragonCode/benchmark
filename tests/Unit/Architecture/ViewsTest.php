<?php

declare(strict_types=1);

arch()
    ->expect('DragonCode\Benchmark\View')
    ->toBeClasses();

arch()
    ->expect('DragonCode\Benchmark\View')
    ->toHaveSuffix('View');
