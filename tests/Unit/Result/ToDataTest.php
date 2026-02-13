<?php

declare(strict_types=1);

test('named array', function () {
    $result = benchmark()
        ->compare([
            'foo' => static fn () => usleep(50),
            'bar' => static fn () => true,
        ])
        ->toData();

    expect($result['foo'])
        ->min->time->toBeGreaterThan(5)
        ->max->time->toBeGreaterThan(5)
        ->avg->time->toBeGreaterThan(5)
        ->total->time->toBeGreaterThan(5);

    expect($result['bar'])
        ->min->time->toBeLessThan(1)
        ->max->time->toBeLessThan(1)
        ->avg->time->toBeLessThan(1)
        ->total->time->toBeLessThan(1);
});

test('array without names', function () {
    $result = benchmark()
        ->compare([
            static fn () => usleep(50),
            static fn () => true,
        ])
        ->toData();

    expect($result[0])
        ->min->time->toBeGreaterThan(5)
        ->max->time->toBeGreaterThan(5)
        ->avg->time->toBeGreaterThan(5)
        ->total->time->toBeGreaterThan(5);

    expect($result[1])
        ->min->time->toBeLessThan(1)
        ->max->time->toBeLessThan(1)
        ->avg->time->toBeLessThan(1)
        ->total->time->toBeLessThan(1);
});

test('named callbacks', function () {
    $result = benchmark()
        ->compare(
            foo: static fn () => usleep(50),
            bar: static fn () => true,
        )
        ->toData();

    expect($result['foo'])
        ->min->time->toBeGreaterThan(5)
        ->max->time->toBeGreaterThan(5)
        ->avg->time->toBeGreaterThan(5)
        ->total->time->toBeGreaterThan(5);

    expect($result['bar'])
        ->min->time->toBeLessThan(1)
        ->max->time->toBeLessThan(1)
        ->avg->time->toBeLessThan(1)
        ->total->time->toBeLessThan(1);
});

test('callbacks without names', function () {
    $result = benchmark()
        ->compare(
            static fn () => usleep(50),
            static fn () => true,
        )
        ->toData();

    expect($result[0])
        ->min->time->toBeGreaterThan(5)
        ->max->time->toBeGreaterThan(5)
        ->avg->time->toBeGreaterThan(5)
        ->total->time->toBeGreaterThan(5);

    expect($result[1])
        ->min->time->toBeLessThan(1)
        ->max->time->toBeLessThan(1)
        ->avg->time->toBeLessThan(1)
        ->total->time->toBeLessThan(1);
});
