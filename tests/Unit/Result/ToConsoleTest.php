<?php

declare(strict_types=1);

use DragonCode\Benchmark\View\TableView;

test('output', function () {
    $reflection = new ReflectionClass(TableView::class);
    
    $property = $reflection->getProperty('stream');
    
    $stream = fopen('php://memory', 'r+b');
    $property->setValue(null, $stream);

    benchmark()->toConsole();

    rewind($stream);
    $output = stream_get_contents($stream);
    fclose($stream);

    $property->setValue(null, null);

    expect($output)->toMatchSnapshot();
});
