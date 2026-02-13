<?php

declare(strict_types=1);

namespace Tests\Unit;

test('calls count', function () {
    $result = [];

    benchmark()
        ->after(function () use (&$result) {
            $result[] = 1;
        })
        ->compare([
            fn () => true,
            fn () => true,
        ]);

    expect($result)->toHaveCount(2);
});

test('array with names', function () {
    $result = [];

    benchmark()
        ->after(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare([
            'foo' => fn () => true,
            'bar' => fn () => true,
        ]);

    expect($result)->toBe([
        'foo',
        'bar',
    ]);
});

test('array without names', function () {
    $result = [];

    benchmark()
        ->after(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare([
            fn () => true,
            fn () => true,
        ]);

    expect($result)->toBe([
        '0',
        '1',
    ]);
});

test('callback with names', function () {
    $result = [];

    benchmark()
        ->after(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare(
            foo: fn () => true,
            bar: fn () => true,
        );

    expect($result)->toBe([
        'foo',
        'bar',
    ]);
});

test('callback without names', function () {
    $result = [];

    benchmark()
        ->after(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare(
            fn () => true,
            fn () => true,
        );

    expect($result)->toBe([
        '0',
        '1',
    ]);
});
