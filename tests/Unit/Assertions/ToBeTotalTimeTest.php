<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->iterations(8)
        ->compare(fn () => usleep(50))
        ->assert()
        ->toBeTotalTime(1, 1000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->iterations(8)
        ->compare(fn () => usleep(50))
        ->assert()
        ->toBeTotalTime();

    expect(true)->toBeTrue();
});

test('failure less than', function () {
    benchmark()
        ->iterations(8)
        ->compare(fn () => usleep(10))
        ->assert()
        ->toBeTotalTime(from: 1000);
})->throws(AssertionError::class, 'The total time value must be greater than or equal to 1000.');

test('failure greater than', function () {
    benchmark()
        ->iterations(8)
        ->compare(fn () => usleep(10))
        ->assert()
        ->toBeTotalTime(till: 10);
})->throws(AssertionError::class, 'The total time value must be less than or equal to 10.');
