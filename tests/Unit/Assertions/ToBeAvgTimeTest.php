<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->toAssert()
        ->toBeAvgTime(1, 1000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->toAssert()
        ->toBeAvgTime();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->toAssert()
        ->toBeAvgTime(from: 1000);
})->throws(AssertionError::class, 'The average time value must be greater than or equal to 1000.');

test('failure less than', function () {
    benchmark()
        ->toAssert()
        ->toBeAvgTime(till: 1);
})->throws(AssertionError::class, 'The average time value must be less than or equal to 1.');
