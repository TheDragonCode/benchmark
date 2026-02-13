<?php

declare(strict_types=1);

namespace Tests\Unit;

test('default', function () {
    benchmark()->iterations(2)->compare(
        fn () => $this->work(),
        fn () => $this->work(),
        fn () => $this->work(),
        fn () => $this->work(),
        fn () => $this->work(),
    );

    expect(true)->toBeTrue();
});
