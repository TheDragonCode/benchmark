<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

abstract class Console
{
    protected static $stream;

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
    abstract protected function stream();
}
