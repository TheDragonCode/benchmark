<?php

declare(strict_types=1);

use DragonCode\Benchmark\Benchmark;
use Tests\Fixtures\CollectorFixture;

test('to console', function (int $iterations) {
    (new Benchmark(collector: new CollectorFixture))
        ->iterations($iterations)
        ->toConsole();

    expectOutputToMatchSnapshot();
})->with([3, 1000, 10000]);

test('to data', function (int $iterations) {
    (new Benchmark(collector: new CollectorFixture))
        ->iterations($iterations)
        ->toData();

    expectOutputToMatchSnapshot();
})->with([3, 1000, 10000]);

test('to assert', function (int $iterations) {
    (new Benchmark(collector: new CollectorFixture))
        ->iterations($iterations)
        ->toAssert();

    expectOutputToMatchSnapshot();
})->with([3, 1000, 10000]);
