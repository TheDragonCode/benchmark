<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use RuntimeException;

use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function is_dir;
use function mkdir;
use function sprintf;

class FilesystemService
{
    protected int $permission = 0777;

    public function __construct(
        protected string $location,
    ) {}

    public function ensureDirectoryExists(): void
    {
        if ($this->exists()) {
            return;
        }

        $this->createDirectory();
    }

    public function store(int|string $name, string $content): void
    {
        file_put_contents($this->path($name), $content);
    }

    public function exists(int|string|null $name = null): bool
    {
        return file_exists($this->path($name));
    }

    public function read(int|string $name): string
    {
        return file_get_contents($this->path($name));
    }

    protected function createDirectory(): void
    {
        $created = mkdir($path = $this->location, $this->permission, true);

        if (! $created && ! is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
    }

    protected function path(int|string|null $name): string
    {
        if ($name === null) {
            return $this->location;
        }

        return $this->location . '/' . $name . '.snap';
    }
}
