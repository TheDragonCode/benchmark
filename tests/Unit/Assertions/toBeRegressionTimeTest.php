<?php

declare(strict_types=1);

test('success less than', function () {
    benchmark()
        ->snapshots(__DIR__ . '/../../.benchmarks')
        ->toAssert()
        ->toBeRegressionTime(15);

    expect(true)->toBeTrue();
});

test('success equals', function () {
    benchmark()
        ->snapshots(__DIR__ . '/../../.benchmarks')
        ->toAssert()
        ->toBeRegressionTime(15);

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->snapshots(__DIR__ . '/../../.benchmarks')
        ->toAssert()
        ->toBeRegressionTime(15);
})->throws(
    AssertionError::class,
    'The time regression value must be less than or equal to 15%. Current value: 209913.87%.'
);
