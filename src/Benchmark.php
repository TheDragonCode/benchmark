<?php

declare(strict_types=1);

namespace DragonCode\Benchmark;

use Closure;
use DragonCode\Benchmark\Contracts\ProgressBar;
use DragonCode\Benchmark\Exceptions\NoComparisonsException;
use DragonCode\Benchmark\Services\AssertService;
use DragonCode\Benchmark\Services\CallbacksService;
use DragonCode\Benchmark\Services\CollectorService;
use DragonCode\Benchmark\Services\DeviationService;
use DragonCode\Benchmark\Services\ResultService;
use DragonCode\Benchmark\Services\RunnerService;
use DragonCode\Benchmark\Services\ViewService;
use DragonCode\Benchmark\Transformers\ResultTransformer;

use function abs;
use function count;
use function max;

class Benchmark
{
    protected int $iterations = 100;

    protected int $deviations = 1;

    public function __construct(
        protected RunnerService $runner = new RunnerService,
        protected ViewService $view = new ViewService,
        protected CallbacksService $callbacks = new CallbacksService,
        protected CollectorService $collector = new CollectorService,
        protected ResultService $result = new ResultService,
        protected ResultTransformer $transformer = new ResultTransformer,
        protected DeviationService $deviation = new DeviationService,
    ) {}

    /**
     * Creates a new benchmark instance.
     */
    public static function make(): static
    {
        return new static;
    }

    /**
     * Sets a callback to be executed before all iterations for each comparison.
     *
     * @param  Closure(int|string $name): mixed  $callback
     *
     * @return $this
     */
    public function before(Closure $callback): static
    {
        $this->callbacks->before = $callback;

        return $this;
    }

    /**
     * Sets a callback to be executed before each iteration.
     *
     * @param  Closure(int|string $name, int<1, max> $iteration): mixed  $callback
     *
     * @return $this
     */
    public function beforeEach(Closure $callback): static
    {
        $this->callbacks->beforeEach = $callback;

        return $this;
    }

    /**
     * Sets a callback to be executed after all iterations for each comparison.
     *
     * @param  Closure(int|string $name): mixed  $callback
     *
     * @return $this
     */
    public function after(Closure $callback): static
    {
        $this->callbacks->after = $callback;

        return $this;
    }

    /**
     * Sets a callback to be executed after each iteration.
     *
     * @param  Closure(int|string $name, int<1, max> $iteration, float $time, float $memory): mixed  $callback
     *
     * @return $this
     */
    public function afterEach(Closure $callback): static
    {
        $this->callbacks->afterEach = $callback;

        return $this;
    }

    /**
     * Sets the number of iterations for each comparison.
     *
     * @param  int<1, max>  $count
     *
     * @return $this
     */
    public function iterations(int $count): static
    {
        $this->iterations = max(1, abs($count));

        return $this;
    }

    /**
     * Enables deviation calculation and sets the number of runs.
     *
     * @param  int<2, max>  $count
     *
     * @return $this
     */
    public function deviations(int $count = 2): static
    {
        $this->clear();

        $this->deviations = max(2, abs($count));

        return $this;
    }

    /**
     * Sets the rounding precision for time values.
     *
     * @param  int<0, max>|null  $precision  The number of decimal places. Null means no rounding.
     *
     * @return $this
     */
    public function round(?int $precision): static
    {
        $this->transformer->round($precision);

        return $this;
    }

    /**
     * Disables the progress bar display.
     *
     * @return $this
     */
    public function disableProgressBar(): static
    {
        $this->view->disable();

        return $this;
    }

    /**
     * Registers callback functions for comparison.
     *
     * @param  array|Closure  ...$callbacks  Callback functions or an array of callback functions for comparison.
     *
     * @return $this
     */
    public function compare(array|Closure ...$callbacks): static
    {
        $this->clear();

        $this->callbacks->compare(...$callbacks);

        return $this;
    }

    /**
     * Returns benchmark results as an array of data.
     *
     * @return Data\ResultData[]
     */
    public function toData(): array
    {
        if (! $this->result->has()) {
            $this->perform();
        }

        return $this->mapResult();
    }

    /**
     * Outputs benchmark results to the console as a table.
     */
    public function toConsole(): void
    {
        if (! $data = $this->toData()) {
            $this->view->line('[INFO] No comparisons were made.');

            return;
        }

        $this->view->table(
            $this->transformer->toTable($data)
        );
    }

    /**
     * Returns the assertion service for performing result checks.
     */
    public function toAssert(): AssertService
    {
        if (! $data = $this->toData()) {
            throw new NoComparisonsException;
        }

        return new AssertService($data);
    }

    /**
     * Performs the benchmark: simple comparison or with deviation calculation.
     */
    protected function perform(): void
    {
        $this->deviations === 1
            ? $this->performCompare()
            : $this->performDeviation();
    }

    /**
     * Transforms collected data into an array of results.
     */
    protected function mapResult(): array
    {
        return $this->result->get(
            $this->collector->all()
        );
    }

    /**
     * Performs a simple comparison of callback functions.
     */
    protected function performCompare(): void
    {
        $callbacks = $this->callbacks->compare;

        $this->withProgress(
            callback: fn (ProgressBar $bar) => $this->chunks($callbacks, $bar),
            total   : $this->steps($callbacks)
        );
    }

    /**
     * Performs a comparison with deviation calculation through multiple runs.
     */
    protected function performDeviation(): void
    {
        $results = [];

        $callbacks = $this->callbacks->compare;

        $this->withProgress(function (ProgressBar $bar) use (&$results, $callbacks) {
            for ($i = 1; $i <= $this->deviations; $i++) {
                $this->clear();

                $this->chunks($callbacks, $bar);

                $results[] = $this->mapResult();
            }
        }, $this->steps($callbacks, $this->deviations));

        $this->clear();

        $this->result->force(
            $this->deviation->calculate($results)
        );
    }

    /**
     * Wraps execution in a progress bar.
     *
     * @param  Closure  $callback  The callback function to execute.
     * @param  int  $total  The total number of progress bar steps.
     */
    protected function withProgress(Closure $callback, int $total): void
    {
        $this->view->emptyLine();

        $bar = $this->view->progressBar()->create($total);

        $callback($bar);

        $bar->finish();
        $this->view->emptyLine(2);
    }

    /**
     * Calculates the total number of steps for the progress bar.
     *
     * @param  array  $callbacks  An array of callback functions.
     * @param  int  $multiplier  The multiplier (number of runs).
     */
    protected function steps(array $callbacks, int $multiplier = 1): int
    {
        return count($callbacks) * $this->iterations * $multiplier;
    }

    /**
     * Executes all callback functions with before/after hooks.
     *
     * @param  array  $callbacks  An array of callback functions.
     * @param  ProgressBar  $progressBar  The progress bar.
     */
    protected function chunks(array $callbacks, ProgressBar $progressBar): void
    {
        foreach ($callbacks as $name => $callback) {
            $this->callbacks->performBefore($name);

            $this->run($name, $callback, $progressBar);

            $this->callbacks->performAfter($name);
        }
    }

    /**
     * Executes all iterations for a single callback function.
     *
     * @param  mixed  $name  The callback name.
     * @param  Closure  $callback  The callback function to execute.
     * @param  ProgressBar  $progressBar  The progress bar.
     */
    protected function run(mixed $name, Closure $callback, ProgressBar $progressBar): void
    {
        for ($i = 1; $i <= $this->iterations; $i++) {
            $result = $this->callbacks->performBeforeEach($name, $i);

            [$time, $memory] = $this->call($callback, [$i, $result]);

            $this->callbacks->performAfterEach($name, $i, $time, $memory);

            $this->push($name, $time, $memory);

            $progressBar->advance();
        }
    }

    /**
     * Calls a callback function and returns the measurement results.
     *
     * @param  Closure  $callback  The callback function to execute.
     * @param  array  $parameters  Parameters to pass to the callback.
     *
     * @return array An array [time in milliseconds, memory in bytes].
     */
    protected function call(Closure $callback, array $parameters = []): array
    {
        return $this->runner->call($callback, $parameters);
    }

    /**
     * Stores measurement results in the collector.
     *
     * @param  mixed  $name  The callback name.
     * @param  float  $time  Execution time is specified in milliseconds.
     * @param  float  $memory  Memory usage is specified in bytes.
     */
    protected function push(mixed $name, float $time, float $memory): void
    {
        $this->collector->push($name, [$time, $memory]);
    }

    /**
     * Clears results and collected data.
     */
    protected function clear(): void
    {
        $this->result->clear();
        $this->collector->clear();
    }
}
