<?php

declare(strict_types=1);

test('to console', function () {
    benchmark()
        ->disableProgressBar()
        ->toConsole();

    expectOutputToMatchSnapshot();
});

test('to data', function () {
    benchmark()
        ->disableProgressBar()
        ->toData();

    expectOutputToMatchSnapshot();
});

test('to assert', function () {
    benchmark()
        ->disableProgressBar()
        ->toAssert();

    expectOutputToMatchSnapshot();
});
