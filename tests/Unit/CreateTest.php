<?php

declare(strict_types=1);

namespace Tests\Unit;

use DragonCode\Benchmark\Benchmark;

test('as dynamic', function () {
    expect(new Benchmark())->toBeInstanceOf(Benchmark::class);
});

test('as static', function () {
    expect(Benchmark::make())->toBeInstanceOf(Benchmark::class);
});
