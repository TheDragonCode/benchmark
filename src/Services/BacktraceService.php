<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use function array_reverse;
use function debug_backtrace;
use function getcwd;
use function realpath;
use function str_contains;
use function str_replace;
use function trim;

class BacktraceService
{
    public function path(): string
    {
        return trim($this->resolve(), '\\/');
    }

    protected function resolve(): string
    {
        return str_replace([$this->callPath(), '.php'], '', $this->scriptPath());
    }

    protected function scriptPath(): string
    {
        return $this->normalize(
            path: $this->findPath()
        );
    }

    protected function callPath(): string
    {
        return $this->normalize(
            path: getcwd() ?: '.'
        );
    }

    protected function findPath(): string
    {
        foreach (array_reverse($this->trace()) as $item) {
            if (! $path = $item['file'] ?? false) {
                continue;
            }

            if (str_contains($path, 'vendor')) {
                continue;
            }

            return $path;
        }

        return 'unknown';
    }

    protected function normalize(string $path): string
    {
        return realpath($path);
    }

    protected function trace(): array
    {
        return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    }
}
