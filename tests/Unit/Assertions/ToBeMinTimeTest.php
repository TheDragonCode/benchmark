<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->compare(fn () => usleep(50))
        ->assert()
        ->toBeMinTime(1, 100);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->compare(fn () => usleep(50))
        ->assert()
        ->toBeMinTime();

    expect(true)->toBeTrue();
});

test('failure less than', function () {
    benchmark()
        ->compare(fn () => usleep(10))
        ->assert()
        ->toBeMinTime(from: 100);
})->throws(AssertionError::class, 'The minimum time value must be greater than or equal to 100.');

test('failure greater than', function () {
    benchmark()
        ->compare(fn () => usleep(10))
        ->assert()
        ->toBeMinTime(till: 1);
})->throws(AssertionError::class, 'The minimum time value must be less than or equal to 1.');
