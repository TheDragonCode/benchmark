<?php

declare(strict_types=1);

namespace DragonCode\Benchmark;

use Closure;
use DragonCode\Benchmark\Exceptions\ValueIsNotCallableException;
use DragonCode\Benchmark\Services\AssertService;
use DragonCode\Benchmark\Services\Callbacks;
use DragonCode\Benchmark\Services\Collector;
use DragonCode\Benchmark\Services\Result;
use DragonCode\Benchmark\Services\Runner;
use DragonCode\Benchmark\Services\View;
use DragonCode\Benchmark\Transformers\Transformer;
use DragonCode\Benchmark\View\ProgressBarView;

use function abs;
use function count;
use function func_get_args;
use function is_array;
use function is_callable;
use function max;

class Benchmark
{
    protected int $iterations = 100;

    public function __construct(
        protected Runner $runner = new Runner,
        protected Transformer $transformer = new Transformer,
        protected View $view = new View,
        protected Callbacks $callbacks = new Callbacks,
        protected Collector $collector = new Collector,
        protected Result $result = new Result,
    ) {}

    public static function make(): static
    {
        return new static;
    }

    public function before(Closure $callback): self
    {
        $this->callbacks->before = $callback;

        return $this;
    }

    public function beforeEach(Closure $callback): self
    {
        $this->callbacks->beforeEach = $callback;

        return $this;
    }

    public function after(Closure $callback): self
    {
        $this->callbacks->after = $callback;

        return $this;
    }

    public function afterEach(Closure $callback): self
    {
        $this->callbacks->afterEach = $callback;

        return $this;
    }

    public function iterations(int $count): self
    {
        $this->iterations = max(1, abs($count));

        return $this;
    }

    public function round(?int $precision): self
    {
        $this->view->setRound($precision);

        return $this;
    }

    public function compare(array|Closure ...$callbacks): static
    {
        $values = match (true) {
            is_array($callbacks[0]) => $callbacks[0],
            is_array($callbacks)    => $callbacks,
            default                 => func_get_args()
        };

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

    public function toConsole(): void
    {
        $stats  = $this->transformer->forStats($this->result);
        $winner = $this->transformer->forWinners($stats);

        $this->view->table($this->transformer->merge($stats, $winner));
    }

    public function assert(): AssertService
    {
        return new AssertService(
            $this->toData()
        );
    }

    protected function withProgress(array $callbacks, int $count): void
    {
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
