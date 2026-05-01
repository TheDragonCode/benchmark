<?php

declare(strict_types=1);

use DragonCode\Benchmark\Benchmark;
use Tests\Fixtures\CollectorFixture;

test('warmup', function (int $warmup) {
    $count = 0;

    (new Benchmark(collector: new CollectorFixture))
        ->iterations(3)
        ->round(4)
        ->warmup($warmup)
        ->compare(
            foo: function () use (&$count) {
                $count++;
            },
            bar: function () use (&$count) {
                $count++;
            },
        )
        ->toConsole();

    expect($count)->toBe(6 + $warmup * 2);

    expectOutputToMatchSnapshot();
})->with([1, 5, 10]);
