<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->assert()
        ->toBeAvgMemory(1, 100);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->assert()
        ->toBeAvgMemory();

    expect(true)->toBeTrue();
});

test('failure less than', function () {
    benchmark()
        ->assert()
        ->toBeAvgMemory(from: 100);
})->throws(AssertionError::class, 'The average memory value must be greater than or equal to 100.');

test('failure greater than', function () {
    benchmark()
        ->assert()
        ->toBeAvgMemory(till: 1);
})->throws(AssertionError::class, 'The average memory value must be less than or equal to 1.');
