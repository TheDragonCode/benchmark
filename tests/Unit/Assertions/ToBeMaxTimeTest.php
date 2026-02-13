<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->compare(fn () => usleep(50))
        ->assert()
        ->toBeMaxTime(1, 100);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->compare(fn () => usleep(50))
        ->assert()
        ->toBeMaxTime();

    expect(true)->toBeTrue();
});

test('failure less than', function () {
    benchmark()
        ->compare(fn () => usleep(10))
        ->assert()
        ->toBeMaxTime(from: 100);
})->throws(AssertionError::class, 'The maximum time value must be greater than or equal to 100.');

test('failure greater than', function () {
    benchmark()
        ->compare(fn () => usleep(10))
        ->assert()
        ->toBeMaxTime(till: 1);
})->throws(AssertionError::class, 'The maximum time value must be less than or equal to 1.');
