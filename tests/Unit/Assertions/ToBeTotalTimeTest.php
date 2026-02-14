<?php

declare(strict_types=1);

test('success', function () {
    benchmark()
        ->assert()
        ->toBeTotalTime(1, 1000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark()
        ->assert()
        ->toBeTotalTime();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->assert()
        ->toBeTotalTime(from: 1000);
})->throws(AssertionError::class, 'The total time value must be greater than or equal to 1000.');

test('failure less than', function () {
    benchmark()
        ->assert()
        ->toBeTotalTime(till: 10);
})->throws(AssertionError::class, 'The total time value must be less than or equal to 10.');
