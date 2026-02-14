<?php

declare(strict_types=1);

namespace DragonCode\Benchmark;

use Closure;
use DragonCode\Benchmark\Exceptions\ValueIsNotCallableException;
use DragonCode\Benchmark\Services\AssertService;
use DragonCode\Benchmark\Services\CallbacksService;
use DragonCode\Benchmark\Services\CollectorService;
use DragonCode\Benchmark\Services\ResultService;
use DragonCode\Benchmark\Services\RunnerService;
use DragonCode\Benchmark\Services\ViewService;
use DragonCode\Benchmark\Transformers\ResultTransformer;
use DragonCode\Benchmark\View\ProgressBarView;

use function abs;
use function array_first;
use function count;
use function func_get_args;
use function is_array;
use function is_callable;
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

    public function before(Closure $callback): static
    {
        $this->callbacks->before = $callback;

        return $this;
    }

    public function beforeEach(Closure $callback): static
    {
        $this->callbacks->beforeEach = $callback;

        return $this;
    }

    public function after(Closure $callback): static
    {
        $this->callbacks->after = $callback;

        return $this;
    }

    public function afterEach(Closure $callback): static
    {
        $this->callbacks->afterEach = $callback;

        return $this;
    }

    public function iterations(int $count): static
    {
        $this->iterations = max(1, abs($count));

        return $this;
    }

    public function round(?int $precision): static
    {
        $this->transformer->round($precision);

        return $this;
    }

    public function compare(array|Closure ...$callbacks): static
    {
        $values = $this->resolveCallbacks(
            func_get_args() ?: $callbacks
        );

        $this->clear();

        $this->withProgress($values, $this->steps($values));

        return $this;
    }

    /**
     * @return \DragonCode\Benchmark\Data\ResultData[]
     */
    public function toData(): array
    {
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

    protected function withProgress(array $callbacks, int $count): void
    {
        $this->view->emptyLine();

        $bar = $this->view->progressBar()->create($count);

        $this->chunks($callbacks, $bar);

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
            $this->validate($callback);

            $this->callbacks->performBefore($name);

            $this->run($name, $callback, $progressBar);

            $this->callbacks->performAfter($name);
        }
    }

    protected function run(mixed $name, Closure $callback, ProgressBarView $progressBar): void
    {
        for ($i = 1; $i <= $this->iterations; ++$i) {
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

    protected function resolveCallbacks(array $callbacks): array
    {
        $first = array_first($callbacks);

        return is_array($first) ? $first : $callbacks;
    }

    protected function validate(mixed $callback): void
    {
        if (! is_callable($callback)) {
            throw new ValueIsNotCallableException($callback);
        }
    }

    protected function clear(): void
    {
        $this->result->clear();
        $this->collector->clear();
    }
}
