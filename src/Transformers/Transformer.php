<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\Transformers;

use DragonCode\Benchmark\Contracts\Transformer as TransformerContract;

class Transformer
{
    public function forTime(array $data): array
    {
        return $this->resolve(Times::class, $data);
    }

    public function forStats(array $data): array
    {
        return $this->resolve(Stats::class, $data);
    }

    public function forWinners(array $data): array
    {
        return $this->resolve(Winner::class, $data);
    }

    public function separator(array $data): array
    {
        return $this->resolve(Separator::class, $data);
    }

    public function merge(array ...$arrays): array
    {
        $result = [];

        $count = count($arrays);
        $index = 1;

        foreach ($arrays as $array) {
            if (! empty($array)) {
                $result = $index < $count
                    ? array_merge($result, $array, $this->separator($arrays[0]))
                    : array_merge($result, $array);
            }

            ++$index;
        }

        return $result;
    }

    protected function resolve(TransformerContract|string $transformer, array $data): array
    {
        return (new $transformer())->transform($data);
    }
}
