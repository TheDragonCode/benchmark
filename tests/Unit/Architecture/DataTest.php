<?php

declare(strict_types=1);

arch()
    ->expect('DragonCode\Benchmark\Data')
    ->toBeClasses();

arch()
    ->expect('DragonCode\Benchmark\Data')
    ->toBeReadonly();

arch()
    ->expect('DragonCode\Benchmark\Data')
    ->toHaveSuffix('Data');
