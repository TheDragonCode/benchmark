<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use function assert;

class AssertService
{
    /**
     * @param  \DragonCode\Benchmark\Data\ResultData[]  $result
     */
    public function __construct(
        protected array $result
    ) {}

    public function toBeMinTime(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->min->time, $from, 'minimum time');
            $this->assertLessThan($item->min->time, $till, 'minimum time');
        }

        return $this;
    }

    public function toBeMaxTime(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->max->time, $from, 'maximum time');
            $this->assertLessThan($item->max->time, $till, 'maximum time');
        }

        return $this;
    }

    public function toBeAvgTime(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->avg->time, $from, 'average time');
            $this->assertLessThan($item->avg->time, $till, 'average time');
        }

        return $this;
    }

    public function toBeTotalTime(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->total->time, $from, 'total time');
            $this->assertLessThan($item->total->time, $till, 'total time');
        }

        return $this;
    }

    public function toBeMinMemory(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->min->memory, $from, 'minimum memory');
            $this->assertLessThan($item->min->memory, $till, 'minimum memory');
        }

        return $this;
    }

    public function toBeMaxMemory(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->max->memory, $from, 'maximum memory');
            $this->assertLessThan($item->max->memory, $till, 'maximum memory');
        }

        return $this;
    }

    public function toBeAvgMemory(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->avg->memory, $from, 'average memory');
            $this->assertLessThan($item->avg->memory, $till, 'average memory');
        }

        return $this;
    }

    public function toBeTotalMemory(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->total->memory, $from, 'total memory');
            $this->assertLessThan($item->total->memory, $till, 'total memory');
        }

        return $this;
    }

    protected function assertGreaterThan(?float $actual, ?float $expected, string $name): void
    {
        if ($expected === null) {
            return;
        }

        assert($actual >= $expected, "The $name value must be greater than or equal to $expected.");
    }

    protected function assertLessThan(?float $actual, ?float $expected, string $name): void
    {
        if ($expected === null) {
            return;
        }

        assert($actual <= $expected, "The $name value must be less than or equal to $expected.");
    }

    protected function resolveFrom(?float $from, ?float $till): ?float
    {
        if ($from === null && $till === null) {
            return 0;
        }

        return $from;
    }
}
