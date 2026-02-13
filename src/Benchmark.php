<?php

declare(strict_types=1);

namespace DragonCode\Benchmark;

use Closure;
use DragonCode\Benchmark\Exceptions\ValueIsNotCallableException;
use DragonCode\Benchmark\Services\Runner;
use DragonCode\Benchmark\Services\View;
use DragonCode\Benchmark\Transformers\Transformer;
use Symfony\Component\Console\Helper\ProgressBar as ProgressBarService;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

use function count;
use function func_get_args;
use function gettype;
use function is_array;
use function is_callable;
use function max;

class Benchmark
{
    protected View $view;

    protected int $iterations = 100;

    protected ?Closure $beforeEach = null;

    protected ?Closure $afterEach = null;

    protected array $result = [
        'each'  => [],
        'total' => [],
    ];

    public function __construct(
        protected Runner $runner = new Runner,
        protected Transformer $transformer = new Transformer
    ) {
        $this->view = new View(
            new SymfonyStyle(
                new ArgvInput,
                new ConsoleOutput
            )
        );
    }

    public static function start(): static
    {
        return new static;
    }

    public function beforeEach(callable $callback): self
    {
        $this->beforeEach = $callback;

        return $this;
    }

    public function afterEach(callable $callback): self
    {
        $this->afterEach = $callback;

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

    public function compare(array|callable ...$callbacks): void
    {
        $values = is_array($callbacks[0]) ? $callbacks[0] : func_get_args();

        $this->withProgress($values, $this->stepsCount($values));
        $this->show();
    }

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

    protected function chunks(array $callbacks, ProgressBarService $progressBar): void
    {
        foreach ($callbacks as $name => $callback) {
            $this->validate($callback);

            $this->each($name, $callback, $progressBar);
        }
    }

    protected function each(mixed $name, callable $callback, ProgressBarService $progressBar): void
    {
        $this->result['total'][$name] = $this->call(
            fn () => $this->run($name, $callback, $progressBar)
        );
    }

    protected function run(mixed $name, callable $callback, ProgressBarService $progressBar): void
    {
        for ($i = 1; $i <= $this->iterations; ++$i) {
            $result = $this->runCallback($this->beforeEach, $name, $i);

            [$time, $ram] = $this->call($callback, [$i, $result]);

            $this->runCallback($this->afterEach, $name, $i, $time, $ram);

            $this->push($name, $i, $time, $ram);

            $progressBar->advance();
        }
    }

    protected function runCallback(?Closure $callback, mixed ...$arguments): mixed
    {
        if (! $callback) {
            return null;
        }

        return $callback(...$arguments);
    }

    protected function call(callable $callback, array $parameters = []): array
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
