<?php

declare(strict_types=1);

namespace Tests\Unit;

use DragonCode\Benchmark\Exceptions\ValueIsNotCallableException;
use TypeError;

test('properties', function () {
    benchmark()->compare(123);
})->throws(TypeError::class, 'must be of type Closure|array, int given');

test('named array', function () {
    benchmark()->compare([
        'foo' => 123,
    ]);
})->throws(ValueIsNotCallableException::class, 'The array value must be of type Closure, integer given.');

test('unnamed array', function () {
    benchmark()->compare([
        123,
    ]);
})->throws(ValueIsNotCallableException::class, 'The array value must be of type Closure, integer given.');

test('callback', function () {
    benchmark()->compare(
        fn () => 123,
    );
})->throws(ValueIsNotCallableException::class, 'The array value must be of type Closure, integer given.');
