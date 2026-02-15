<?php

declare(strict_types=1);

namespace Tests\Unit;

test('calls count', function () {
    $result = [];

    benchmark()
        ->before(function () use (&$result) {
            $result[] = 1;
        })
        ->compare([
            fn () => true,
            fn () => true,
        ])
        ->toData();

    expect($result)->toHaveCount(2);
});

test('array with names', function () {
    $result = [];

    benchmark()
        ->before(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare([
            'foo' => fn () => true,
            'bar' => fn () => true,
        ])
        ->toData();

    expect($result)->toBe([
        'foo',
        'bar',
    ]);
});

test('array without names', function () {
    $result = [];

    benchmark()
        ->before(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare([
            fn () => true,
            fn () => true,
        ])
        ->toData();

    expect($result)->toBe([
        0,
        1,
    ]);
});

test('callback with names', function () {
    $result = [];

    benchmark()
        ->before(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare(
            foo: fn () => true,
            bar: fn () => true,
        )
        ->toData();

    expect($result)->toBe([
        'foo',
        'bar',
    ]);
});

test('callback without names', function () {
    $result = [];

    benchmark()
        ->before(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare(
            fn () => true,
            fn () => true,
        )
        ->toData();

    expect($result)->toBe([
        0,
        1,
    ]);
});
