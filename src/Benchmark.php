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

class Benchmark
{
    protected View $view;

    protected int $iterations = 100;

    protected bool $withData = true;

    protected ?Closure $prepare = null;

    protected array $result = [
        'each'  => [],
        'total' => [],
    ];

    public function __construct(
        protected Runner $runner = new Runner(),
        protected Transformer $transformer = new Transformer()
    ) {
        $this->view = new View(
            new SymfonyStyle(
                new ArgvInput(),
                new ConsoleOutput()
            )
        );
    }

    public function prepare(callable $callback): self
    {
        $this->prepare = $callback;

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

    public function withoutData(): self
    {
        $this->withData = false;

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
            $result = $this->runPrepare($name, $i);

            [$time, $ram] = $this->call($callback, [$i, $result]);

            $this->push($name, $i, $time, $ram);

            $progressBar->advance();
        }
    }

    protected function runPrepare(mixed $name, int $iteration): mixed
    {
        if ($callback = $this->prepare) {
            return $callback($name, $iteration);
        }

        return null;
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
        $table = $this->withData() ? $this->transformer->forTime($this->result['each']) : [];

        $stats  = $this->transformer->forStats($this->result);
        $winner = $this->transformer->forWinners($stats);

        $this->view->table($this->transformer->merge($table, $stats, $winner));
    }

    protected function withData(): bool
    {
        return $this->withData && $this->iterations <= 1000;
    }

    protected function validate(mixed $callback): void
    {
        if (! is_callable($callback)) {
            throw new ValueIsNotCallableException(gettype($callback));
        }
    }
}
