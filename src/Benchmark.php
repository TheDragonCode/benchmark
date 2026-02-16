<?php

declare(strict_types=1);

namespace DragonCode\Benchmark;

use Closure;
use DragonCode\Benchmark\Exceptions\NoComparisonsException;
use DragonCode\Benchmark\Services\AssertService;
use DragonCode\Benchmark\Services\CallbacksService;
use DragonCode\Benchmark\Services\CollectorService;
use DragonCode\Benchmark\Services\DeviationService;
use DragonCode\Benchmark\Services\ResultService;
use DragonCode\Benchmark\Services\RunnerService;
use DragonCode\Benchmark\Services\ViewService;
use DragonCode\Benchmark\Transformers\ResultTransformer;
use DragonCode\Benchmark\View\ProgressBarView;

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

    public static function make(): static
    {
        return new static;
    }

    /**
     * @param  Closure(int|string $name): mixed  $callback
     */
    public function before(Closure $callback): static
    {
        $this->callbacks->before = $callback;

        return $this;
    }

    /**
     * @param  Closure(int|string $name, int<1, max> $iteration): mixed  $callback
     */
    public function beforeEach(Closure $callback): static
    {
        $this->callbacks->beforeEach = $callback;

        return $this;
    }

    /**
     * @param  Closure(int|string $name): mixed  $callback
     */
    public function after(Closure $callback): static
    {
        $this->callbacks->after = $callback;

        return $this;
    }

    /**
     * @param  Closure(int|string $name, int<1, max> $iteration, float $time, float $memory): mixed  $callback
     */
    public function afterEach(Closure $callback): static
    {
        $this->callbacks->afterEach = $callback;

        return $this;
    }

    /**
     * @param  int<1, max>  $count
     */
    public function iterations(int $count): static
    {
        $this->iterations = max(1, abs($count));

        return $this;
    }

    /**
     * @param  int<2, max>  $count
     */
    public function deviations(int $count = 2): static
    {
        $this->clear();

        $this->deviations = max(2, abs($count));

        return $this;
    }

    /**
     * @param  int<0, max>|null  $precision
     */
    public function round(?int $precision): static
    {
        $this->transformer->round($precision);

        return $this;
    }

    public function compare(array|Closure ...$callbacks): static
    {
        $this->clear();

        $this->callbacks->compare(...$callbacks);

        return $this;
    }

    /**
     * @return Data\ResultData[]
     */
    public function toData(): array
    {
        if (! $this->result->has()) {
            $this->perform();
        }

        return $this->mapResult();
    }

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

    public function toAssert(): AssertService
    {
        if (! $data = $this->toData()) {
            throw new NoComparisonsException;
        }

        return new AssertService($data);
    }

    protected function perform(): void
    {
        $this->deviations === 1
            ? $this->performCompare()
            : $this->performDeviation();
    }

    protected function mapResult(): array
    {
        return $this->result->get(
            $this->collector->all()
        );
    }

    protected function performCompare(): void
    {
        $callbacks = $this->callbacks->compare;

        $this->withProgress(
            callback: fn (ProgressBarView $bar) => $this->chunks($callbacks, $bar),
            total   : $this->steps($callbacks)
        );
    }

    protected function performDeviation(): void
    {
        $results = [];

        $callbacks = $this->callbacks->compare;

        $this->withProgress(function (ProgressBarView $bar) use (&$results, $callbacks) {
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

    protected function withProgress(Closure $callback, int $total): void
    {
        $this->view->emptyLine();

        $bar = $this->view->progressBar()->create($total);

        $callback($bar);

        $bar->finish();
        $this->view->emptyLine(2);
    }

    protected function steps(array $callbacks, int $multiplier = 1): int
    {
        return count($callbacks) * $this->iterations * $multiplier;
    }

    protected function chunks(array $callbacks, ProgressBarView $progressBar): void
    {
        foreach ($callbacks as $name => $callback) {
            $this->callbacks->performBefore($name);

            $this->run($name, $callback, $progressBar);

            $this->callbacks->performAfter($name);
        }
    }

    protected function run(mixed $name, Closure $callback, ProgressBarView $progressBar): void
    {
        for ($i = 1; $i <= $this->iterations; $i++) {
            $result = $this->callbacks->performBeforeEach($name, $i);

            [$time, $memory] = $this->call($callback, [$i, $result]);

            $this->callbacks->performAfterEach($name, $i, $time, $memory);

            $this->push($name, $time, $memory);

            $progressBar->advance();
        }
    }

    protected function call(Closure $callback, array $parameters = []): array
    {
        return $this->runner->call($callback, $parameters);
    }

    protected function push(mixed $name, float $time, float $memory): void
    {
        $this->collector->push($name, [$time, $memory]);
    }

    protected function clear(): void
    {
        $this->result->clear();
        $this->collector->clear();
    }
}
