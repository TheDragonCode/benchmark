<?php

declare(strict_types=1);

arch()->preset()->php()->ignoring([
    'debug_backtrace',
]);

arch()->preset()->security()->ignoring([
    'assert',
    'unserialize',
]);
