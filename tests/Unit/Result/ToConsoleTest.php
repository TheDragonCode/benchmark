<?php

declare(strict_types=1);

test('output', function () {
    benchmark()->toConsole();

    expectOutputToMatchSnapshot();
});
