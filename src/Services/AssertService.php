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

    public function toBeMinTime(float $from, float $till): void
    {
        foreach ($this->result as $item) {
            assert($item->min->time >= $from);
            assert($item->min->time <= $till);
        }
    }

    public function toBeMaxTime(float $from, float $till): void
    {
        foreach ($this->result as $item) {
            assert($item->max->time >= $from);
            assert($item->max->time <= $till);
        }
    }

    public function toBeAvgTime(float $from, float $till): void
    {
        foreach ($this->result as $item) {
            assert($item->avg->time >= $from);
            assert($item->avg->time <= $till);
        }
    }

    public function toBeTotalTime(float $from, float $till): void
    {
        foreach ($this->result as $item) {
            assert($item->total->time >= $from);
            assert($item->total->time <= $till);
        }
    }

    public function toBeMinMemory(float $from, float $till): void
    {
        foreach ($this->result as $item) {
            assert($item->min->memory >= $from);
            assert($item->min->memory <= $till);
        }
    }

    public function toBeMaxMemory(float $from, float $till): void
    {
        foreach ($this->result as $item) {
            assert($item->max->memory >= $from);
            assert($item->max->memory <= $till);
        }
    }

    public function toBeAvgMemory(float $from, float $till): void
    {
        foreach ($this->result as $item) {
            assert($item->avg->memory >= $from);
            assert($item->avg->memory <= $till);
        }
    }

    public function toBeTotalMemory(float $from, float $till): void
    {
        foreach ($this->result as $item) {
            assert($item->total->memory >= $from);
            assert($item->total->memory <= $till);
        }
    }
}
