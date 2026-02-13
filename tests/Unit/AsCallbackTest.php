<?php

declare(strict_types=1);

namespace Tests\Unit;

test('default', function () {
    benchmark()->compare(
        fn () => $this->work(),
        fn () => $this->work(),
    );

    expect(true)->toBeTrue();
});

test('iterations', function () {
    benchmark()->iterations(5)->compare(
        fn () => $this->work(),
        fn () => $this->work(),
    );

    benchmark()->iterations(500)->compare(
        fn () => $this->work(),
        fn () => $this->work(),
    );

    expect(true)->toBeTrue();
});

test('without data', function () {
    benchmark()->compare(
        fn () => $this->work(),
        fn () => $this->work(),
    );

    expect(true)->toBeTrue();
});

test('round', function () {
    benchmark()->round(2)->iterations(5)->compare(
        fn () => $this->work(),
        fn () => $this->work(),
    );

    expect(true)->toBeTrue();
});
