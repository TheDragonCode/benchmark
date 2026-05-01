<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use RuntimeException;

use function debug_backtrace;
use function getcwd;
use function in_array;
use function realpath;
use function str_replace;
use function trim;

class BacktraceService
{
    protected string $detectClass = AssertService::class;

    protected array $detectMethods = [
        'toBeRegressionTime',
        'toBeRegressionMemory',
    ];

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
        foreach ($this->trace() as $item) {
            $class  = $item['class']    ?? false;
            $method = $item['function'] ?? false;
            $file   = $item['file']     ?? false;
            $line   = $item['line']     ?? 0;

            if ($class !== $this->detectClass) {
                continue;
            }

            if (! in_array($method, $this->detectMethods, true)) {
                continue;
            }

            return $this->normalize($file) . '_' . $line;
        }

        throw new RuntimeException('Unable to resolve the script path.');
    }

    protected function callPath(): string
    {
        return $this->normalize(
            path: getcwd() ?: '.'
        );
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
