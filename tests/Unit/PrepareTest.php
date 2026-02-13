<?php

declare(strict_types=1);

namespace Tests\Unit;

test('callback', function () {
    $result = [];

    benchmark()
        ->iterations(3)
        ->beforeEach(function () use (&$result) {
            $result[] = 1;
        })
        ->compare([
            'foo' => fn () => $this->work(),
            'bar' => fn () => $this->work(),
        ]);

    expect(count($result))->toBe(6);
});

test('parameters', function () {
    $result = [];

    benchmark()
        ->iterations(3)
        ->beforeEach(function (mixed $name, int $iteration) use (&$result) {
            $result[] = sprintf('%s:%d', $name, $iteration);
        })
        ->compare([
            'foo' => fn () => $this->work(),
            'bar' => fn () => $this->work(),
        ]);

    expect(count($result))->toBe(6);

    expect($result)->toBe([
        'foo:1',
        'foo:2',
        'foo:3',
        'bar:1',
        'bar:2',
        'bar:3',
    ]);
});

test('name', function () {
    $result = [];

    benchmark()
        ->iterations(3)
        ->beforeEach(function (mixed $name) use (&$result) {
            $result[] = $name;
        })
        ->compare([
            'foo' => fn () => $this->work(),
            'bar' => fn () => $this->work(),
        ]);

    expect(count($result))->toBe(6);

    expect($result)->toBe([
        'foo',
        'foo',
        'foo',
        'bar',
        'bar',
        'bar',
    ]);
});

test('prepare result', function () {
    benchmark()
        ->iterations(3)
        ->beforeEach(
            fn (mixed $name, int $iteration) => sprintf('%s:%d', $name, $iteration)
        )
        ->compare([
            'foo' => fn (int $iteration, string $result) => expect($result)->toBe('foo:' . $iteration),
            'bar' => fn (int $iteration, string $result) => expect($result)->toBe('bar:' . $iteration),
        ]);
});
