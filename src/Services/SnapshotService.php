<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use DragonCode\Benchmark\Data\MetricData;
use DragonCode\Benchmark\Data\ResultData;

use function serialize;
use function unserialize;

class SnapshotService
{
    protected string $location = './.benchmarks';

    protected ?FilesystemService $filesystem = null;

    protected array $deserialized = [];

    public function location(string $location): void
    {
        $this->location = $location;
    }

    public function create(array $data): void
    {
        $this->filesystem()->ensureDirectoryExists();
        $this->store($data);
    }

    public function read(int|string $name): ?ResultData
    {
        if (! $this->filesystem()->exists($name)) {
            return $this->deserialized[$name] ??= null;
        }

        return $this->deserialized[$name] ??= $this->decode(
            $this->filesystem()->read($name)
        );
    }

    protected function store(array $data): void
    {
        foreach ($data as $name => $value) {
            if ($this->filesystem()->exists($name)) {
                continue;
            }

            $this->filesystem()->store($name, $this->encode($value));
        }
    }

    protected function encode(ResultData $data): string
    {
        return serialize($data);
    }

    protected function decode(string $data): ResultData
    {
        return unserialize($data, [
            'allowed_classes' => [
                ResultData::class,
                MetricData::class,
            ],
        ]);
    }

    protected function filesystem(): FilesystemService
    {
        return $this->filesystem ??= new FilesystemService($this->location);
    }
}
