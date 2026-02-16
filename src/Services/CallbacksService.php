<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Services;

use Closure;
use DragonCode\Benchmark\Exceptions\ValueIsNotCallableException;

use function array_first;
use function is_array;

class CallbacksService
{
    public ?Closure $before = null;

    public ?Closure $beforeEach = null;

    public ?Closure $after = null;

    public ?Closure $afterEach = null;

    public array $compare = [];

    /**
     * Registers callback functions for comparison.
     *
     * @param  array<int|string, Closure>|Closure  ...$callbacks  Callback functions or an array of callback functions
     *     for comparison.
     */
    public function compare(array|Closure ...$callbacks): void
    {
        foreach ($this->parameters($callbacks) as $key => $callback) {
            $this->validate($callback);

            $this->compare[$key] = $callback;
        }
    }

    /**
     * Performs actions before the loop starts.
     *
     * @param  int|string  $name  The callback name.
     */
    public function performBefore(int|string $name): mixed
    {
        return $this->perform($this->before, $name);
    }

    /**
     * Performs actions before each iteration in the loop.
     *
     * @param  int|string  $name  The callback name.
     * @param  int  $iteration  The current iteration number.
     */
    public function performBeforeEach(int|string $name, int $iteration): mixed
    {
        return $this->perform($this->beforeEach, $name, $iteration);
    }

    /**
     * Performs actions after the loop finishes.
     *
     * @param  int|string  $name  The callback name.
     */
    public function performAfter(int|string $name): mixed
    {
        return $this->perform($this->after, $name);
    }

    /**
     * Performs actions after each iteration in the loop.
     *
     * @param  int|string  $name  The callback name.
     * @param  int  $iteration  The current iteration number.
     * @param  float  $time  Execution time is specified in milliseconds.
     * @param  float  $memory  Memory usage is specified in bytes.
     */
    public function performAfterEach(int|string $name, int $iteration, float $time, float $memory): mixed
    {
        return $this->perform($this->afterEach, $name, $iteration, $time, $memory);
    }

    /**
     * Performs an action.
     *
     * @param  Closure|null  $callback  The callback function to execute.
     * @param  mixed  ...$args  Arguments to pass to the callback.
     */
    protected function perform(?Closure $callback, mixed ...$args): mixed
    {
        if ($callback === null) {
            return null;
        }

        return $callback(...$args);
    }

    /**
     * Extracts parameters from callback functions.
     *
     * @param  array  $callbacks  An array of callback functions.
     *
     * @return array
     */
    protected function parameters(array $callbacks): array
    {
        $first = array_first($callbacks);

        return is_array($first) ? $first : $callbacks;
    }

    /**
     * Validates that the provided value is a Closure.
     *
     * @param  mixed  $callback  The value to validate.
     */
    protected function validate(mixed $callback): void
    {
        if (! $callback instanceof Closure) {
            throw new ValueIsNotCallableException($callback);
        }
    }
}
