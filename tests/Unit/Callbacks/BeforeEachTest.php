<?php

declare(strict_types=1);

namespace Tests\Unit;

test('calls count', function () {
    $result = [];

    benchmark()
        ->beforeEach(function () use (&$result) {
            $result[] = 1;
        })
        ->compare([
            fn () => true,
            fn () => true,
        ])
        ->toData();

    expect($result)->toHaveCount(6);
});

test('names only', function () {
    $result = [];

    benchmark()
        ->beforeEach(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare([
            'foo' => fn () => true,
            'bar' => fn () => true,
        ])
        ->toData();

    expect($result)->toBe([
        'foo',
        'foo',
        'foo',
        'bar',
        'bar',
        'bar',
    ]);
});

test('iterations', function () {
    $result = [];

    benchmark()
        ->beforeEach(function (mixed $name, int $iteration) use (&$result) {
            $result[] = $name . ':' . $iteration;
        })
        ->compare([
            'foo' => fn () => true,
            'bar' => fn () => true,
        ])
        ->toData();

    expect($result)->toBe([
        'foo:1',
        'foo:2',
        'foo:3',
        'bar:1',
        'bar:2',
        'bar:3',
    ]);
});

test('array without names', function () {
    $result = [];

    benchmark()
        ->beforeEach(function (mixed $name, int $iteration) use (&$result) {
            $result[] = $name . ':' . $iteration;
        })
        ->compare([
            fn () => true,
            fn () => true,
        ])
        ->toData();

    expect($result)->toBe([
        '0:1',
        '0:2',
        '0:3',
        '1:1',
        '1:2',
        '1:3',
    ]);
});

test('callback without names', function () {
    $result = [];

    benchmark()
        ->beforeEach(function (mixed $name, int $iteration) use (&$result) {
            $result[] = $name . ':' . $iteration;
        })
        ->compare(
            fn () => true,
            fn () => true,
        )
        ->toData();

    expect($result)->toBe([
        '0:1',
        '0:2',
        '0:3',
        '1:1',
        '1:2',
        '1:3',
    ]);
});
