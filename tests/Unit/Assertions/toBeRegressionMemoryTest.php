<?php

declare(strict_types=1);

test('success less than', function () {
    benchmark()
        ->snapshots(__DIR__ . '/../../.benchmarks/memory_less_than')
        ->toAssert()
        ->toBeRegressionMemory(15);

    expect(true)->toBeTrue();
});

test('success equals', function () {
    benchmark()
        ->snapshots(__DIR__ . '/../../.benchmarks/memory_equals')
        ->toAssert()
        ->toBeRegressionMemory(15);

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark()
        ->snapshots(__DIR__ . '/../../.benchmarks/memory_greater_than')
        ->toAssert()
        ->toBeRegressionMemory(15);
})->throws(
    AssertionError::class,
    'The memory regression value must be less than or equal to 15%. Current value: 13600%.'
);
