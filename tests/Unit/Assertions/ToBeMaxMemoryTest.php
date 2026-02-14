<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->toAssert()
        ->toBeMaxMemory(1, 10000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->toAssert()
        ->toBeMaxMemory();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->toAssert()
        ->toBeMaxMemory(from: 10000);
})->throws(AssertionError::class, 'The maximum memory value must be greater than or equal to 10000.');

test('failure less than', function () {
    benchmark()
        ->toAssert()
        ->toBeMaxMemory(till: 1);
})->throws(AssertionError::class, 'The maximum memory value must be less than or equal to 1.');
