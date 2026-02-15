<?php

declare(strict_types=1);

namespace DragonCode\Benchmark;

use Closure;
use DragonCode\Benchmark\Services\AssertService;
use DragonCode\Benchmark\Services\CallbacksService;
use DragonCode\Benchmark\Services\CollectorService;
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

    public function __construct(
        protected RunnerService $runner = new RunnerService,
        protected ViewService $view = new ViewService,
        protected CallbacksService $callbacks = new CallbacksService,
        protected CollectorService $collector = new CollectorService,
        protected ResultService $result = new ResultService,
        protected ResultTransformer $transformer = new ResultTransformer
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
     * @param  int<0, max>|null  $precision
     */
    public function round(?int $precision): static
    {
        $this->transformer->round($precision);

        return $this;
    }

    public function compare(array|Closure ...$callbacks): static
    {
        $this->callbacks->compare(...$callbacks);

        return $this;
    }

    /**
     * @return Data\ResultData[]
     */
    public function toData(): array
    {
        $this->performCallbacks();

        return $this->result->get(
            $this->collector->all()
        );
    }

    public function toConsole(): static
    {
        $table = $this->transformer->toTable(
            $this->toData()
        );

        $this->view->table($table);

        return $this;
    }

    public function toAssert(): AssertService
    {
        return new AssertService(
            $this->toData()
        );
    }

    protected function performCallbacks(): void
    {
        $this->clear();

        $callbacks = $this->callbacks->compare;

        $this->withProgress(
            callback: fn (ProgressBarView $bar) => $this->chunks($callbacks, $bar),
            total   : $this->steps($callbacks)
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

    protected function steps(array $callbacks): int
    {
        return count($callbacks) * $this->iterations;
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
