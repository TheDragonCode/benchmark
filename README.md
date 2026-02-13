# Benchmark

<picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://banners.beyondco.de/Benchmark.png?pattern=topography&style=style_2&fontSize=100px&md=1&showWatermark=1&theme=dark&packageManager=composer+require+--dev&packageName=dragon-code%2Fbenchmark&description=Simple+comparison+of+code+execution+speed+between+different+options&images=https%3A%2F%2Fwww.php.net%2Fimages%2Flogos%2Fnew-php-logo.svg">
    <img src="https://banners.beyondco.de/Benchmark.png?pattern=topography&style=style_2&fontSize=100px&md=1&showWatermark=1&theme=light&packageManager=composer+require+--dev&packageName=dragon-code%2Fbenchmark&description=Simple+comparison+of+code+execution+speed+between+different+options&images=https%3A%2F%2Fwww.php.net%2Fimages%2Flogos%2Fnew-php-logo.svg" alt="Benchmark">
</picture>

[![Stable Version][badge_stable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![Github Workflow Status][badge_build]][link_build]
[![License][badge_license]][link_license]

## Installation

To get the latest version of `The Dragon Code: Benchmark`, simply require the project
using [Composer](https://getcomposer.org):

```bash
composer require dragon-code/benchmark --dev
```

Or manually update `require-dev` block of `composer.json` and run `composer update` console command:

```json
{
    "require-dev": {
        "dragon-code/benchmark": "^4.0"
    }
}
```

## Using

> Note
>
> The result of the execution is printed to the console, so make sure you call the code from the console.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()->compare(
    fn () => /* some code */,
    fn () => /* some code */,
)->toConsole();

new Benchmark()->compare([
    fn () => /* some code */,
    fn () => /* some code */,
])->toConsole();

new Benchmark()->compare([
    'foo' => fn () => /* some code */,
    'bar' => fn () => /* some code */,
])->toConsole();
```

Result example:

```
 ------- --------------------- -------------------- 
  #       0                     1                   
 ------- --------------------- -------------------- 
  min     0.001 ms - 14.8Kb     0.001 ms - 4.1Kb    
  max     0.0101 ms - 64.8Kb    0.0026 ms - 13.7Kb  
  avg     0.00122 ms - 47.5Kb   0.0016 ms - 4.1Kb   
  total   0.7998 ms             0.6156 ms
 ------- --------------------- -------------------- 
  Order   - 1 -                 - 2 -               
 ------- --------------------- -------------------- 
```

When measuring the average value among the results, when more than 10 iterations are used, the final data is filtered by
peak values. The calculation of the 10% of the lowest and
10% of the highest values is excluded from the total result, thus the final data becomes cleaner and less dependent on
any external factors.

### Iterations Count

By default, the benchmark performs 100 iterations per callback, but you can change this number by calling
the `iterations` method:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->iterations(5)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

If the passed value is less than 1, then one iteration will be performed for each callback.

```
 ------- --------------------- --------------------- 
  #       0                     1                    
 ------- --------------------- --------------------- 
  min     0.0011 ms - 58.4Kb    0.0011 ms - 55.4Kb   
  max     0.0077 ms - 64.8Kb    0.0015 ms - 57.3Kb   
  avg     0.00272 ms - 60.1Kb   0.00124 ms - 56.2Kb  
  total   0.2453 ms             0.1105 ms
 ------- --------------------- --------------------- 
  Order   - 2 -                 - 1 -                
 ------- --------------------- --------------------- 
```

You can also get the number of the current execution iteration from the input parameter:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->iterations(5)
    ->compare(
        fn (int $iteration) => /* some code */,
        fn (int $iteration) => /* some code */,
    )
    ->toConsole();
```

### Round Precision

By default, the script does not round measurement results, but you can specify the number of decimal places to which
rounding can be performed.

For example:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->iterations(5)
    ->round(2)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

Result example:

```
 ------- ------------------ ------------------ 
  #       0                  1                 
 ------- ------------------ ------------------ 
  min     11.85 ms - 0b      14.94 ms - 0b     
  max     15.4 ms - 0b       15.24 ms - 0b     
  avg     14.37 ms - 0b      15.11 ms - 0b     
  total   73.47 ms           76.03 ms
 ------- ------------------ ------------------ 
  Order   - 1 -              - 2 -             
 ------- ------------------ ------------------ 
```

### Prepare Data

In some cases, it becomes necessary to call some action before starting each check cycle so that its time does not fall
into the result of the runtime check.
There is a `prepare` method for this:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->prepare(fn () => /* some code */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

When calling a callback, the name and iteration parameters are passed to it. If necessary, you can use this information
inside the callback function.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->prepare(fn (mixed $name, int $iteration) => /* some code */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

You can also get the number of the current iteration and the result of the execution of the preliminary function from
the input parameter:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->prepare(fn (mixed $name, int $iteration) => /* some code */)
    ->compare(
        fn (int $iteration, mixed $prepareResult) => /* some code */,
        fn (int $iteration, mixed $prepareResult) => /* some code */,
    )
    ->toConsole();
```

## Assertions

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->assert()
    
    ->toBeMinTime(0.5, 3)       // between 0.5 and 3 ms
    ->toBeMaxTime(0.5, 3)       // between 0.5 and 3 ms
    ->toBeAvgTime(0.5, 3)       // between 0.5 and 3 ms
    ->toBeTotalTime(0.5, 9)     // between 0.5 and 9 ms
    
    ->toBeMinMemory(0, 1024)    // between 0 and 1024 bytes
    ->toBeMaxMemory(0, 1024)    // between 0 and 1024 bytes
    ->toBeAvgMemory(0, 1024)    // between 0 and 1024 bytes
    ->toBeTotalMemory(0, 4096); // between 0 and 4096 bytes
```

You can also use a single value:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->assert()
    
    ->toBeMinTime(0.5)       // time must be greater than or equal to 0.5 ms
    ->toBeMaxTime(0.5)       // time must be greater than or equal to 0.5 ms
    ->toBeAvgTime(0.5)       // time must be greater than or equal to 0.5 ms
    ->toBeTotalTime(0.5)     // time must be greater than or equal to 0.5 ms
    
    ->toBeMinMemory(till: 1024)    // the memory footprint should not exceed 1024 bytes
    ->toBeMaxMemory(till: 1024)    // the memory footprint should not exceed 1024 bytes
    ->toBeAvgMemory(till: 1024)    // the memory footprint should not exceed 1024 bytes
    ->toBeTotalMemory(till: 4096); // the memory footprint should not exceed 4096 bytes
```

## Information

```
 ------- ------------------ ------------------ 
  #       foo                bar                 
 ------- ------------------ ------------------ 
  min     11.33 ms - 0b      14.46 ms - 0b     
  max     15.28 ms - 0b      15.09 ms - 0b     
  avg     14.2 ms - 0b       14.88 ms - 0b     
  total   71.62 ms           75.12 ms
 ------- ------------------ ------------------ 
  Order   - 1 -              - 2 -             
 ------- ------------------ ------------------ 
```

* `foo`, `bar` - The names of the columns in the passed array. Needed for identification. By default, the array index is
  used, starting from zero. For example, `1, 2, 3,.. N+1`.
* `11.33 ms` - Execution time of the checked code in one iteration.
* `0b`, `6.8Kb`, etc. - The amount of RAM used by the checked code.
* `min` - Minimum code processing time.
* `max` - Maximum code processing time.
* `avg` - The arithmetic mean value among all iterations, taking into account the elimination of 10% of the smallest and
  10% of the largest values to obtain a more accurate value
  through the possible intervention of external factors.
* `total` - The total time and RAM spent on checking all iterations of the code.

## License

This package is licensed under the [MIT License](LICENSE).


[badge_build]:          https://img.shields.io/github/actions/workflow/status/TheDragonCode/benchmark/tests.yml?style=flat-square

[badge_downloads]:      https://img.shields.io/packagist/dt/dragon-code/benchmark.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/dragon-code/benchmark.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/TheDragonCode/benchmark?label=stable&style=flat-square

[link_build]:           https://github.com/TheDragonCode/benchmark/actions

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/dragon-code/benchmark
