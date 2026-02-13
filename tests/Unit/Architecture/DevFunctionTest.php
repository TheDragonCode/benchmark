<?php

declare(strict_types=1);

arch()
    ->expect('DragonCode\Benchmark')
    ->not->toUse([
        'dd',
        'die',
        'dump',
        'echo',
        'exit',
        'print_r',
        'printf',
        'var_dump',
    ]);
