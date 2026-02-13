<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->assert()
        ->toBeAvgTime(1, 100);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->assert()
        ->toBeAvgTime();

    expect(true)->toBeTrue();
});

test('failure less than', function () {
    benchmark()
        ->assert()
        ->toBeAvgTime(from: 100);
})->throws(AssertionError::class, 'The average time value must be greater than or equal to 100.');

test('failure greater than', function () {
    benchmark()
        ->assert()
        ->toBeAvgTime(till: 1);
})->throws(AssertionError::class, 'The average time value must be less than or equal to 1.');
