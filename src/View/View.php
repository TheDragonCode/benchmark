<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use function fopen;
use function fwrite;

abstract class View
{
    /** @var resource|null */
    protected static mixed $stream = null;

    /**
     * Writes a line of text with a newline to the output stream.
     *
     * @param  string  $line  The text to write.
     */
    protected function writeLine(string $line): void
    {
        $this->write($line . PHP_EOL);
    }

    /**
     * Writes a line of text to the output stream without a newline.
     *
     * @param  string  $line  The text to write.
     */
    protected function write(string $line): void
    {
        fwrite($this->stream(), $line);
    }

    /**
     * Returns the output stream, creating it on the first call.
     *
     * @return resource
     */
    protected function stream()
    {
        return static::$stream ??= fopen('php://stderr', 'wb');
    }
}
