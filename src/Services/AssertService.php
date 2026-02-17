<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use AssertionError;
use Closure;
use DragonCode\Benchmark\Data\ResultData;
use DragonCode\Benchmark\Exceptions\DeviationsNotCalculatedException;

class AssertService
{
    /**
     * Creates a benchmark result assertion service.
     *
     * @param  ResultData[]  $result
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
        return $this->assertRange($from, $till, static fn (ResultData $item) => $item->min->time, 'minimum time');
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
        return $this->assertRange($from, $till, static fn (ResultData $item) => $item->max->time, 'maximum time');
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
        return $this->assertRange($from, $till, static fn (ResultData $item) => $item->avg->time, 'average time');
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
        return $this->assertRange($from, $till, static fn (ResultData $item) => $item->total->time, 'total time');
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
        return $this->assertRange($from, $till, static fn (ResultData $item) => $item->min->memory, 'minimum memory');
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
        return $this->assertRange($from, $till, static fn (ResultData $item) => $item->max->memory, 'maximum memory');
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
        return $this->assertRange($from, $till, static fn (ResultData $item) => $item->avg->memory, 'average memory');
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
        return $this->assertRange($from, $till, static fn (ResultData $item) => $item->total->memory, 'total memory');
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
        return $this->assertRange($from, $till, static function (ResultData $item, int|string $key) {
            if (! $item->deviation) {
                throw new DeviationsNotCalculatedException($key);
            }

            return $item->deviation->percent->time;
        }, 'deviation time');
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
        return $this->assertRange($from, $till, static function (ResultData $item, int|string $key) {
            if (! $item->deviation) {
                throw new DeviationsNotCalculatedException($key);
            }

            return $item->deviation->percent->memory;
        }, 'deviation memory');
    }

    /**
     * Asserts that the value extracted by the callback is within the specified range for all results.
     *
     * @param  float|null  $from  The start value of the range.
     * @param  float|null  $till  The end value of the range.
     * @param  callable  $callback  Callback to extract the value from a result item.
     * @param  string  $name  The name of the metric being checked.
     * @return $this
     */
    protected function assertRange(?float $from, ?float $till, Closure $callback, string $name): static
    {
        $from = $this->resolveFrom($from, $till);

        foreach ($this->result as $key => $item) {
            $value = $callback($item, $key);

            $this->assertGreaterThan($value, $from, $name);
            $this->assertLessThan($value, $till, $name);
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
