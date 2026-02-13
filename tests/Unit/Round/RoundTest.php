<?php

declare(strict_types=1);

test('default', function () {
    benchmark()->toConsole();

    expectOutputToMatchSnapshot();
});

test('round', function (int $precision) {
    benchmark()->round($precision)->toConsole();

    expectOutputToMatchSnapshot();
})->with([0, 2, 5]);
