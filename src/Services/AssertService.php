<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use AssertionError;
use DragonCode\Benchmark\Exceptions\DeviationsNotCalculatedException;

class AssertService
{
    /**
     * Creates a benchmark result assertion service.
     *
     * @param  \DragonCode\Benchmark\Data\ResultData[]  $result
     */
    public function __construct(
        protected array $result
    ) {}

    /**
     * Asserts that the minimum execution time is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in milliseconds.
     * @param  float|null  $till  End value is specified in milliseconds.
     * @return $this
     */
    public function toBeMinTime(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->min->time, $from, 'minimum time');
            $this->assertLessThan($item->min->time, $till, 'minimum time');
        }

        return $this;
    }

    /**
     * Asserts that the maximum execution time is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in milliseconds.
     * @param  float|null  $till  End value is specified in milliseconds.
     * @return $this
     */
    public function toBeMaxTime(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->max->time, $from, 'maximum time');
            $this->assertLessThan($item->max->time, $till, 'maximum time');
        }

        return $this;
    }

    /**
     * Asserts that the average execution time is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in milliseconds.
     * @param  float|null  $till  End value is specified in milliseconds.
     * @return $this
     */
    public function toBeAvgTime(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->avg->time, $from, 'average time');
            $this->assertLessThan($item->avg->time, $till, 'average time');
        }

        return $this;
    }

    /**
     * Asserts that the total execution time is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in milliseconds.
     * @param  float|null  $till  End value is specified in milliseconds.
     * @return $this
     */
    public function toBeTotalTime(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->total->time, $from, 'total time');
            $this->assertLessThan($item->total->time, $till, 'total time');
        }

        return $this;
    }

    /**
     * Asserts that the minimum memory usage is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in bytes.
     * @param  float|null  $till  End value is specified in bytes.
     * @return $this
     */
    public function toBeMinMemory(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->min->memory, $from, 'minimum memory');
            $this->assertLessThan($item->min->memory, $till, 'minimum memory');
        }

        return $this;
    }

    /**
     * Asserts that the maximum memory usage is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in bytes.
     * @param  float|null  $till  End value is specified in bytes.
     * @return $this
     */
    public function toBeMaxMemory(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->max->memory, $from, 'maximum memory');
            $this->assertLessThan($item->max->memory, $till, 'maximum memory');
        }

        return $this;
    }

    /**
     * Asserts that the average memory usage is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in bytes.
     * @param  float|null  $till  End value is specified in bytes.
     * @return $this
     */
    public function toBeAvgMemory(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->avg->memory, $from, 'average memory');
            $this->assertLessThan($item->avg->memory, $till, 'average memory');
        }

        return $this;
    }

    /**
     * Asserts that the total memory usage is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in bytes.
     * @param  float|null  $till  End value is specified in bytes.
     * @return $this
     */
    public function toBeTotalMemory(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $item) {
            $this->assertGreaterThan($item->total->memory, $from, 'total memory');
            $this->assertLessThan($item->total->memory, $till, 'total memory');
        }

        return $this;
    }

    /**
     * Asserts that the execution time deviation is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in percentages.
     * @param  float|null  $till  End value is specified in percentages.
     * @return $this
     */
    public function toBeDeviationTime(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $key => $item) {
            if (! $item->deviation) {
                throw new DeviationsNotCalculatedException($key);
            }

            $this->assertGreaterThan($item->deviation->percent->time, $from, 'deviation time');
            $this->assertLessThan($item->deviation->percent->time, $till, 'deviation time');
        }

        return $this;
    }

    /**
     * Asserts that the memory usage deviation is within the specified range.
     *
     * @param  float|null  $from  Start value is specified in percentages.
     * @param  float|null  $till  End value is specified in percentages.
     * @return $this
     */
    public function toBeDeviationMemory(?float $from = null, ?float $till = null): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $name => $item) {
            if (! $item->deviation) {
                throw new DeviationsNotCalculatedException($name);
            }

            $this->assertGreaterThan($item->deviation->percent->memory, $from, 'deviation memory');
            $this->assertLessThan($item->deviation->percent->memory, $till, 'deviation memory');
        }

        return $this;
    }

    /**
     * Asserts that the actual value is greater than or equal to the expected value.
     *
     * @param  float  $actual  The actual value.
     * @param  float|null  $expected  The expected minimum value.
     * @param  string  $name  The name of the metric being checked.
     */
    protected function assertGreaterThan(float $actual, ?float $expected, string $name): void
    {
        if ($expected === null) {
            return;
        }

        if ($actual >= $expected) {
            return;
        }

        throw new AssertionError(
            "The $name value must be greater than or equal to $expected."
        );
    }

    /**
     * Asserts that the actual value is less than or equal to the expected value.
     *
     * @param  float  $actual  The actual value.
     * @param  float|null  $expected  The expected maximum value.
     * @param  string  $name  The name of the metric being checked.
     */
    protected function assertLessThan(float $actual, ?float $expected, string $name): void
    {
        if ($expected === null) {
            return;
        }

        if ($actual <= $expected) {
            return;
        }

        throw new AssertionError(
            "The $name value must be less than or equal to $expected."
        );
    }

    /**
     * Resolves the start value of the range. Returns 0 if both parameters are null.
     *
     * @param  float|null  $from  The start value of the range.
     * @param  float|null  $till  The end value of the range.
     */
    protected function resolveFrom(?float $from, ?float $till): ?float
    {
        if ($from === null && $till === null) {
            return 0;
        }

        return $from;
    }
}
