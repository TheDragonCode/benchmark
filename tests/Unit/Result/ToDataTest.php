<?php

declare(strict_types=1);

test('named array', function () {
    $result = benchmark()->toData();

    expect($result[0])
        ->min->time->toBeGreaterThan(3)
        ->max->time->toBeGreaterThan(3)
        ->avg->time->toBeGreaterThan(3)
        ->total->time->toBeGreaterThan(3);

    expect($result[1])
        ->min->time->toBeLessThan(10)
        ->max->time->toBeLessThan(10)
        ->avg->time->toBeLessThan(10)
        ->total->time->toBeLessThan(10);
});
