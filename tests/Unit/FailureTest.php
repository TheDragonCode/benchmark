<?php

declare(strict_types=1);

namespace Tests\Unit;

use DragonCode\Benchmark\Exceptions\ValueIsNotCallableException;
use TypeError;

test('as properties', function () {
    benchmark()->compare(123);
})->throws(TypeError::class, 'must be of type Closure|array, int given');

test('as array', function () {
    benchmark()->compare([
        'first'  => 123,
        'second' => 123,
    ]);
})->throws(ValueIsNotCallableException::class, 'The array value must be of type callable, integer given.');

test('as properties with iterations', function () {
    benchmark()->iterations(5)->compare(123);
})->throws(TypeError::class, 'must be of type Closure|array, int given');

test('as array with iterations', function () {
    benchmark()->iterations(5)->compare([
        'first'  => 123,
        'second' => 123,
    ]);
})->throws(ValueIsNotCallableException::class, 'The array value must be of type callable, integer given.');

test('as properties without data', function () {
    benchmark()->compare(123);
})->throws(TypeError::class, 'must be of type Closure|array, int given');

test('as array without data', function () {
    benchmark()->compare([
        'first'  => 123,
        'second' => 123,
    ]);
})->throws(ValueIsNotCallableException::class, 'The array value must be of type callable, integer given.');
