<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->toAssert()
        ->toBeTotalMemory(1, 10000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->toAssert()
        ->toBeTotalMemory();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->toAssert()
        ->toBeTotalMemory(from: 10000);
})->throws(AssertionError::class, 'The total memory value must be greater than or equal to 10000.');

test('failure less than', function () {
    benchmark()
        ->toAssert()
        ->toBeTotalMemory(till: 10);
})->throws(AssertionError::class, 'The total memory value must be less than or equal to 10.');
