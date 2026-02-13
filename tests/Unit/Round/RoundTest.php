<?php

declare(strict_types=1);

test('default', function () {
    benchmark()->toConsole();

    expectOutputToMatchSnapshot();
});

test('to 5', function () {
    benchmark()->round(5)->toConsole();

    expectOutputToMatchSnapshot();
});

test('to 2', function () {
    benchmark()->round(2)->toConsole();

    expectOutputToMatchSnapshot();
});

test('to 0', function () {
    benchmark()->round(0)->toConsole();

    expectOutputToMatchSnapshot();
});
