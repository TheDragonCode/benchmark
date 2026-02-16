<?php

declare(strict_types=1);

use DragonCode\Benchmark\Exceptions\NoComparisonsException;

describe('without comparisons', function () {
    test('toData', function () {
        $result = benchmark(false)->toData();

        expect($result)->toBeEmpty();
    });

    test('toConsole', function () {
        benchmark(false)->toConsole();

        expectOutputToMatchSnapshot();
    });

    test('toAssert', function () {
        benchmark(false)->toAssert();
    })->throws(NoComparisonsException::class);
});
