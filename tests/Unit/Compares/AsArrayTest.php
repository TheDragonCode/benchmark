<?php

declare(strict_types=1);

namespace Tests\Unit;

test('named', function () {
    $results = benchmark()->compare([
        'foo' => fn () => true,
        'bar' => fn () => true,
    ])->toData();

    expect($results)->toHaveKeys([
        'foo',
        'bar',
    ]);
});

test('without names', function () {
    $results = benchmark()->compare([
        fn () => true,
        fn () => true,
    ])->toData();

    expect($results)->toHaveKeys([
        0,
        1,
    ]);
});
