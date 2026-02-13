<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->assert()
        ->toBeMinTime(1, 100);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->assert()
        ->toBeMinTime();

    expect(true)->toBeTrue();
});

test('failure less than', function () {
    benchmark()
        ->assert()
        ->toBeMinTime(from: 100);
})->throws(AssertionError::class, 'The minimum time value must be greater than or equal to 100.');

test('failure greater than', function () {
    benchmark()
        ->assert()
        ->toBeMinTime(till: 1);
})->throws(AssertionError::class, 'The minimum time value must be less than or equal to 1.');
