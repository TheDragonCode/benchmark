<?php

declare(strict_types=1);

use DragonCode\Benchmark\View\View;

test('output', function () {
    benchmark()->toConsole();

    $reflection = new ReflectionClass(View::class);

    $property = $reflection->getProperty('stream');

    $stream = $property->getValue();

    rewind($stream);

    $output = stream_get_contents($stream);

    expect($output)->toMatchSnapshot();
});
