<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->assert()
        ->toBeAvgMemory(1, 10000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->assert()
        ->toBeAvgMemory();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->assert()
        ->toBeAvgMemory(from: 10000);
})->throws(AssertionError::class, 'The average memory value must be greater than or equal to 10000.');

test('failure less than', function () {
    benchmark()
        ->assert()
        ->toBeAvgMemory(till: 1);
})->throws(AssertionError::class, 'The average memory value must be less than or equal to 1.');
