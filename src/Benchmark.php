<?php

declare(strict_types=1);

namespace DragonCode\Benchmark;

use Closure;
use DragonCode\Benchmark\Exceptions\ValueIsNotCallableException;
use DragonCode\Benchmark\Services\Callbacks;
use DragonCode\Benchmark\Services\Runner;
use DragonCode\Benchmark\Services\View;
use DragonCode\Benchmark\Transformers\Transformer;
use DragonCode\Benchmark\View\ProgressBarView;

use function count;
use function func_get_args;
use function gettype;
use function is_array;
use function is_callable;
use function max;

class Benchmark
{
    protected int $iterations = 100;

    protected array $result = [
        'each'  => [],
        'total' => [],
    ];

    public function __construct(
        protected Runner $runner = new Runner,
        protected Transformer $transformer = new Transformer,
        protected View $view = new View,
        protected Callbacks $callbacks = new Callbacks,
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
        $this->iterations = max(1, $count);

        return $this;
    }

    public function round(?int $precision): self
    {
        $this->view->setRound($precision);

        return $this;
    }

    public function compare(array|Closure ...$callbacks): static
    {
        $values = is_array($callbacks[0]) ? $callbacks[0] : func_get_args();

        $this->withProgress($values, $this->stepsCount($values));
        $this->show();

        return $this;
    }

    /**
     * @return \DragonCode\Benchmark\Data\ResultData[]
     */
    public function toData(): array {}

    public function toConsole(): void {}

    protected function withProgress(array $callbacks, int $count): void
    {
        $bar = $this->view->progressBar()->create($count);

        $this->chunks($callbacks, $bar);

        $bar->finish();
        $this->view->emptyLine(2);
    }

    protected function stepsCount(array $callbacks): int
    {
        return count($callbacks) * $this->iterations;
    }

    protected function chunks(array $callbacks, ProgressBarView $progressBar): void
    {
        foreach ($callbacks as $name => $callback) {
            $this->validate($callback);

            $this->each($name, $callback, $progressBar);
        }
    }

    protected function each(mixed $name, Closure $callback, ProgressBarView $progressBar): void
    {
        $this->result['total'][$name] = $this->call(
            fn () => $this->run($name, $callback, $progressBar)
        );
    }

    protected function run(mixed $name, Closure $callback, ProgressBarView $progressBar): void
    {
        $this->callbacks->performBefore($name);

        for ($i = 1; $i <= $this->iterations; ++$i) {
            $result = $this->callbacks->performBeforeEach($name, $i);

            [$time, $ram] = $this->call($callback, [$i, $result]);

            $this->callbacks->performAfterEach($name, $i, $time, $ram);

            $this->push($name, $i, $time, $ram);

            $progressBar->advance();
        }

        $this->callbacks->performAfter($name);
    }

    protected function call(Closure $callback, array $parameters = []): array
    {
        return $this->runner->call($callback, $parameters);
    }

    protected function push(mixed $name, int $iteration, float $time, float $ram): void
    {
        $this->result['each'][$name][$iteration]['time'] = $time;
        $this->result['each'][$name][$iteration]['ram']  = $ram;
    }

    protected function show(): void
    {
        $stats  = $this->transformer->forStats($this->result);
        $winner = $this->transformer->forWinners($stats);

        $this->view->table($this->transformer->merge($stats, $winner));
    }

    protected function validate(mixed $callback): void
    {
        if (! is_callable($callback)) {
            throw new ValueIsNotCallableException(gettype($callback));
        }
    }
}
