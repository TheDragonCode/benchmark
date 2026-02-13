<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->assert()
        ->toBeMinTime(1, 1000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->assert()
        ->toBeMinTime();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->assert()
        ->toBeMinTime(from: 1000);
})->throws(AssertionError::class, 'The minimum time value must be greater than or equal to 1000.');

test('failure less than', function () {
    benchmark()
        ->assert()
        ->toBeMinTime(till: 1);
})->throws(AssertionError::class, 'The minimum time value must be less than or equal to 1.');
