<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->assert()
        ->toBeTotalMemory(1, 10000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->assert()
        ->toBeTotalMemory();

    expect(true)->toBeTrue();
});

test('failure less than', function () {
    benchmark()
        ->assert()
        ->toBeTotalMemory(from: 10000);
})->throws(AssertionError::class, 'The total memory value must be greater than or equal to 10000.');

test('failure greater than', function () {
    benchmark()
        ->assert()
        ->toBeTotalMemory(till: 10);
})->throws(AssertionError::class, 'The total memory value must be less than or equal to 10.');
