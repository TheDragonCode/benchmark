<?php

declare(strict_types=1);

use DragonCode\Benchmark\Exceptions\DeviationsNotCalculatedException;
use Tests\Fixtures\DeviationCollectorFixture;

test('success', function () {
    benchmark(collector: new DeviationCollectorFixture)
        ->deviations()
        ->toAssert()
        ->toBeDeviationMemory(1, 10000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark(collector: new DeviationCollectorFixture)
        ->deviations()
        ->toAssert()
        ->toBeDeviationMemory();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark(collector: new DeviationCollectorFixture)
        ->deviations()
        ->toAssert()
        ->toBeDeviationMemory(from: 10000);
})->throws(AssertionError::class, 'The deviation memory value must be greater than or equal to 10000.');

test('failure less than', function () {
    benchmark(collector: new DeviationCollectorFixture)
        ->deviations()
        ->toAssert()
        ->toBeDeviationMemory(till: 10);
})->throws(AssertionError::class, 'The deviation memory value must be less than or equal to 10.');

test('without deviations call', function () {
    benchmark()
        ->toAssert()
        ->toBeDeviationMemory();
})->throws(DeviationsNotCalculatedException::class);
