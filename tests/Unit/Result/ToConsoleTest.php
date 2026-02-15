<?php

declare(strict_types=1);

test('compare', function () {
    benchmark()->toConsole();

    expectOutputToMatchSnapshot();
});

test('deviations', function () {
    benchmark()
        ->deviations()
        ->toConsole();

    expectOutputToMatchSnapshot();
});
