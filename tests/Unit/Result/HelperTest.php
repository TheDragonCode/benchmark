<?php

declare(strict_types=1);

use function DragonCode\Benchmark\bench;

test('compare', function () {
    bench()
        ->compare(fn () => true)
        ->toData();

    expectOutputToMatchSnapshot();
});

test('deviations', function () {
    bench()
        ->deviations()
        ->compare(fn () => true)
        ->toData();

    expectOutputToMatchSnapshot();
});
