<?php

declare(strict_types=1);

namespace Tests\Unit;

test('default', function () {
    benchmark()->compare([
        'foo' => fn () => $this->work(),
        'bar' => fn () => $this->work(),
    ]);

    expect(true)->toBeTrue();
});

test('iterations', function () {
    benchmark()->iterations(5)->compare([
        'foo' => fn () => $this->work(),
        'bar' => fn () => $this->work(),
    ]);

    benchmark()->iterations(500)->compare([
        'foo' => fn () => $this->work(),
        'bar' => fn () => $this->work(),
    ]);

    expect(true)->toBeTrue();
});

test('without data', function () {
    benchmark()->compare([
        'foo' => fn () => $this->work(),
        'bar' => fn () => $this->work(),
    ]);

    expect(true)->toBeTrue();
});

test('round', function () {
    benchmark()->round(2)->compare([
        'foo' => fn () => $this->work(),
        'bar' => fn () => $this->work(),
    ]);

    expect(true)->toBeTrue();
});
