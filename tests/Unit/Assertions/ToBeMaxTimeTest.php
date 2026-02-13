<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->assert()
        ->toBeMaxTime(1, 1000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->assert()
        ->toBeMaxTime();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->assert()
        ->toBeMaxTime(from: 1000);
})->throws(AssertionError::class, 'The maximum time value must be greater than or equal to 1000.');

test('failure less than', function () {
    benchmark()
        ->assert()
        ->toBeMaxTime(till: 1);
})->throws(AssertionError::class, 'The maximum time value must be less than or equal to 1.');
