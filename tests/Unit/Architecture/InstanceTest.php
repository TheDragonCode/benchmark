<?php

declare(strict_types=1);

namespace Tests\Unit;

use DragonCode\Benchmark\Benchmark;

use function DragonCode\Benchmark\bench;

test('dynamic', function () {
    expect(new Benchmark)->toBeInstanceOf(Benchmark::class);
});

test('static', function () {
    expect(Benchmark::make())->toBeInstanceOf(Benchmark::class);
});

test('helper', function () {
    expect(bench())->toBeInstanceOf(Benchmark::class);
});
