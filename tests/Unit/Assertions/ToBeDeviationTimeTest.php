<?php

declare(strict_types=1);

use DragonCode\Benchmark\Exceptions\DeviationsNotCalculatedException;
use Tests\Fixtures\DeviationCollectorFixture;

test('success', function () {
    benchmark(collector: new DeviationCollectorFixture)
        ->deviations()
        ->toAssert()
        ->toBeDeviationTime(1, 1000);

    expect(true)->toBeTrue();
});

test('success without arguments', function () {
    benchmark(collector: new DeviationCollectorFixture)
        ->deviations()
        ->toAssert()
        ->toBeDeviationTime();

    expect(true)->toBeTrue();
});

test('failure greater than', function () {
    benchmark(collector: new DeviationCollectorFixture)
        ->deviations()
        ->toAssert()
        ->toBeDeviationTime(from: 1000);
})->throws(AssertionError::class, 'The deviation time value must be greater than or equal to 1000.');

test('failure less than', function () {
    benchmark(collector: new DeviationCollectorFixture)
        ->deviations()
        ->toAssert()
        ->toBeDeviationTime(till: 10);
})->throws(AssertionError::class, 'The deviation time value must be less than or equal to 10.');

test('without deviations call', function () {
    benchmark()
        ->toAssert()
        ->toBeDeviationTime();
})->throws(DeviationsNotCalculatedException::class);
