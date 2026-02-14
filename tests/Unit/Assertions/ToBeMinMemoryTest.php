<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->toAssert()
        ->toBeMinMemory(1, 10000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->toAssert()
        ->toBeMinMemory();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->toAssert()
        ->toBeMinMemory(from: 10000);
})->throws(AssertionError::class, 'The minimum memory value must be greater than or equal to 10000.');

test('failure less than', function () {
    benchmark()
        ->toAssert()
        ->toBeMinMemory(till: 1);
})->throws(AssertionError::class, 'The minimum memory value must be less than or equal to 1.');
