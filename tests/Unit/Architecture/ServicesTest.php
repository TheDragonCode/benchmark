<?php

declare(strict_types=1);

arch()
    ->expect('DragonCode\Benchmark\Services')
    ->toBeClasses();

arch()
    ->expect('DragonCode\Benchmark\Services')
    ->toHaveSuffix('Service');
