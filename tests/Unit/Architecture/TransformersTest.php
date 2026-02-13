<?php

declare(strict_types=1);

arch()
    ->expect('DragonCode\Benchmark\Transformers')
    ->toBeClasses();

arch()
    ->expect('DragonCode\Benchmark\Transformers')
    ->toHaveSuffix('Transformer');
