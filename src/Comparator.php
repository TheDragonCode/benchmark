<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison;

use DragonCode\RuntimeComparison\Exceptions\ValueIsNotCallableException;
use DragonCode\RuntimeComparison\Services\Runner;
use DragonCode\RuntimeComparison\Services\View;
use DragonCode\RuntimeComparison\Transformers\Transformer;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

class Comparator
{
    protected View $view;

    protected int $iterations = 10;

    protected array $result = [];

    public function __construct(
        protected Runner      $runner = new Runner(),
        protected Transformer $transformer = new Transformer()
    ) {
        $this->view = new View(new SymfonyStyle(
            new ArgvInput(),
            new ConsoleOutput()
        ));
    }

    public function iterations(int $count): self
    {
        $this->iterations = max(1, $count);

        return $this;
    }

    public function compare(array|callable ...$callbacks): void
    {
        $values = is_array($callbacks[0]) ? $callbacks[0] : func_get_args();

        $this->each($values);
        $this->show();
    }

    protected function each(array $callbacks): void
    {
        foreach ($callbacks as $name => $callback) {
            $this->validate($callback);

            $this->run($name, $callback);
        }
    }

    protected function run(mixed $name, callable $callback): void
    {
        for ($i = 1; $i <= $this->iterations; $i++) {
            $time = $this->call($callback);

            $this->push($name, $i, $time);
        }
    }

    protected function call(callable $callback): float
    {
        return $this->runner->call($callback);
    }

    protected function push(mixed $name, int $iteration, float $time): void
    {
        $this->result[$name][$iteration] = $time;
    }

    protected function show(): void
    {
        $table  = $this->transformer->forTime($this->result);
        $stats  = $this->transformer->forStats($this->result);
        $winner = $this->transformer->forWinners($stats);

        $this->view->table($this->transformer->merge($table, $stats, $winner));
    }

    protected function validate(mixed $callback): void
    {
        if (! is_callable($callback)) {
            throw new ValueIsNotCallableException(gettype($callback));
        }
    }
}
