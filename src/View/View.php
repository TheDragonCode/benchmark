<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use function fopen;
use function fwrite;

abstract class View
{
    protected static $stream;

    protected string $streamName;

    protected function writeLine(string $line): void
    {
        fwrite($this->stream(), $line . PHP_EOL);
    }

    protected function write(string $line): void
    {
        fwrite($this->stream(), $line);
    }

    /**
     * @return resource
     */
    protected function stream()
    {
        return static::$stream ??= fopen($this->streamName, 'wb');
    }
}
