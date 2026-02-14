<?php

declare(strict_types=1);

use DragonCode\Benchmark\View\View;
use PHPUnit\Framework\TestCase;

pest()
    ->printer()
    ->compact();

pest()
    ->extend(TestCase::class)
    ->in('Unit')
    ->beforeEach(function () {
        $reflection = new ReflectionClass(View::class);

        $property = $reflection->getProperty('stream');

        $property->setValue(null, fopen('php://memory', 'r+b'));
    })
    ->afterEach(function () {
        $reflection = new ReflectionClass(View::class);

        $property = $reflection->getProperty('stream');

        $stream = $property->getValue();

        if (is_resource($stream)) {
            fclose($stream);
        }

        $property->setValue(null, null);
    });
