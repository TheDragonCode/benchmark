<?php

declare(strict_types=1);

namespace Tests\Unit;

test('memory', function () {
    $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras efficitur nisi in scelerisque ultricies.';
    $count = 100000;

    $process = function () use ($lorem, $count): array {
        $result = [];

        for ($i = 0; $i < $count; ++$i) {
            $result[] = $lorem;
        }

        return $result;
    };

    benchmark()->iterations(10)->compare(
        fn () => $process(),
        fn () => $process()
    );

    expect(true)->toBeTrue();
});
