<?php

declare(strict_types=1);

namespace DragonCode\RuntimeComparison;

use DragonCode\RuntimeComparison\Services\Math;
use DragonCode\RuntimeComparison\Services\Runner;
use DragonCode\RuntimeComparison\Services\View;

class Comparator
{
    protected int $iterations = 10;

    protected array $result = [];

    public function __construct(
        protected Runner $runner = new Runner(),
        protected View   $view = new View(),
        protected Math   $math = new Math()
    ) {
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
        $this->view->table($this->result);
        $this->view->stats($this->math->stats($this->result));

        $this->view->emptyLine();

        $this->view->info($this->math->winnerBy('min', $this->result));
        $this->view->info($this->math->winnerBy('max', $this->result));
        $this->view->info($this->math->winnerBy('avg', $this->result));
    }
}
