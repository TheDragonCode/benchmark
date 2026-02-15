<?php

declare(strict_types=1);

namespace Tests\Unit;

test('named', function () {
    $results = benchmark(false)->compare(
        foo: fn () => true,
        bar: fn () => true,
    )->toData();

    expect($results)->toHaveKeys([
        'foo',
        'bar',
    ]);
});

test('without names', function () {
    $results = benchmark(false)->compare(
        fn () => true,
        fn () => true,
    )->toData();

    expect($results)->toHaveKeys([
        0,
        1,
    ]);
});

test('mixed names', function () {
    $results = benchmark(false)->compare(
        static fn () => true,
        foo: static fn () => true,
        bar: static fn () => false,
    )->toData();

    expect($results)->toHaveKeys([
        0,
        'foo',
        'bar',
    ]);
});
