<?php

declare(strict_types=1);

test('array', function () {
    $result = benchmark()->toData();

    expect($result[0])
        ->min->time->toBeGreaterThan(3)
        ->max->time->toBeGreaterThan(3)
        ->avg->time->toBeGreaterThan(3)
        ->total->time->toBeGreaterThan(3);

    expect($result[1])
        ->min->time->toBeLessThan(1000)
        ->max->time->toBeLessThan(1000)
        ->avg->time->toBeLessThan(1000)
        ->total->time->toBeLessThan(1000);
});
