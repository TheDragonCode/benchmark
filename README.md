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

To get the latest version of `Benchmark`, simply require the project using [Composer](https://getcomposer.org):

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

## Usage

> Note
>
> The result of the execution is printed to the console, so make sure you call the code from the console.

### Quick Start

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->compare(
        static fn () => true,
        static fn () => true
    )
    ->toConsole();
```

### How To Use

```php
use DragonCode\Benchmark\Benchmark;

// Array without named keys
new Benchmark()->compare([
    fn () => /* some code */,
    fn () => /* some code */,
])->toConsole();

// Array with named keys
new Benchmark()->compare([
    'foo' => fn () => /* some code */,
    'bar' => fn () => /* some code */,
])->toConsole();

// Callbacks without named parameters
new Benchmark()->compare(
    fn () => /* some code */,
    fn () => /* some code */,
)->toConsole();

// Callbacks with named parameters
new Benchmark()->compare(
    foo: fn () => /* some code */,
    bar: fn () => /* some code */,
)->toConsole();
```

Example output with named keys:

```bash
+-------+-------------------------+-------------------------+
| #     | foo                     | bar                     |
+-------+-------------------------+-------------------------+
| min   | 14.3472 ms - 0 bytes    | 14.3657 ms - 0 bytes    |
| max   | 15.7684 ms - 0 bytes    | 15.7249 ms - 0 bytes    |
| avg   | 15.0967475 ms - 0 bytes | 14.9846725 ms - 0 bytes |
| total | 1207.7398 ms - 0 bytes  | 1198.7738 ms - 0 bytes  |
+-------+-------------------------+-------------------------+
| order | 2                       | 1                       |
+-------+-------------------------+-------------------------+
```

When measuring the average value among the results, when more than 9 iterations are used, the final data is filtered by
peak values. The calculation of the 10% of the lowest and 10% of the highest values is excluded from the total result,
thus the final data becomes cleaner and less dependent on any external factors.

### Iterations Count

By default, the benchmark performs 100 iterations per callback, but you can change this number by calling
the `iterations` method:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->iterations(5)
    ->compare([
        'foo' => fn () => /* some code */,
        'bar' => fn () => /* some code */,
    ])
    ->toConsole();
```

If a negative value is passed, its absolute value will be used.

For example:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->iterations(-20) // Will result in 20 iterations
    // ...
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

This method only affects the console output (the `toConsole` method).

For example:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->round(2)
    ->compare([
        'foo' => fn () => /* some code */,
        'bar' => fn () => /* some code */,
    ])
    ->toConsole();
```

Result example:

```bash
+-------+----------------------+----------------------+
| #     | foo                  | bar                  |
+-------+----------------------+----------------------+
| min   | 14.58 ms - 0 bytes   | 14.38 ms - 0 bytes   |
| max   | 15.55 ms - 0 bytes   | 15.71 ms - 0 bytes   |
| avg   | 15.01 ms - 0 bytes   | 15.1 ms - 0 bytes    |
| total | 1201.09 ms - 0 bytes | 1207.76 ms - 0 bytes |
+-------+----------------------+----------------------+
| order | 1                    | 2                    |
+-------+----------------------+----------------------+
```

### Deviation Values

In some cases, it is necessary to test not only the functionality of the options themselves, but also to determine the
level of deviation between them. To do this, use the `deviations` method.

When you specify this method during a call, all your loops will repeat the specified number of times.
Meanwhile, the resulting table and DTO will display values from the results, where:

- `min` - minimum among all result values
- `max` - maximum among all results
- `avg` - arithmetic mean of all result values
- `total` - the total value among the values of all results
- `deviation` - deviation calculated from the mean of all results

For example:

```php
new Benchmark()
    ->deviations(4)
    ->iterations(5)
    ->compare(
        foo: fn () => /* some code */,
        bar: fn () => /* some code */,
    )
    ->toConsole();
```

```bash
+------------------+----------------------+-----------------------+
| #                | foo                  | bar                   |
+------------------+----------------------+-----------------------+
| min              | 0.0011 ms - 0 bytes  | 0.0009 ms - 0 bytes   |
| max              | 0.0111 ms - 0 bytes  | 0.0082 ms - 0 bytes   |
| avg              | 0.00453 ms - 0 bytes | 0.002715 ms - 0 bytes |
| total            | 0.0906 ms - 0 bytes  | 0.0543 ms - 0 bytes   |
+------------------+----------------------+-----------------------+
| order            | 2                    | 1                     |
+------------------+----------------------+-----------------------+
| deviation time   | +0.002768            | +0.000919             |
| deviation memory | 0                    | 0                     |
+------------------+----------------------+-----------------------+
```

#### To understand work without deviations

```php
$iterations = 10;
$result = [];

for ($i = 0; $i < $iterations; $i++) {
    $result[] = /* perform some code */;
}

return $result;
```

#### To understand work with deviations

```php
$iterations = 10;
$deviations = 4;
$result = [];

for ($i = 0; $i < $deviations; $i++) {
    $insideResult = [];

    for ($j = 0; $j < $iterations; $j++) {
        $insideResult[] = /* perform some code */;
    }
    
    $result[] = $this->performResult($insideResult);
}

return $result;
```

### Callbacks

#### Before

In some cases, you may need to perform certain actions before running the benchmark loop.

You can do this by calling the `before` method with a callback.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->before(fn () => /* some code */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

The loop name is passed to the callback as a parameter. You can use this information if needed.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->before(fn (int|string $name) => /* some code */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

#### BeforeEach

In some cases, you may need to perform certain actions before each benchmark iteration.

You can do this by calling the `beforeEach` method with a callback.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->beforeEach(fn () => /* some code */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

The loop name and iteration number are passed to the callback as parameters.
Additionally, the result of the `beforeEach` callback is passed to the compare callback itself.
You can use this information if needed.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->beforeEach(fn (int|string $name, int $iteration) => /* some code */)
    ->compare(
        fn (mixed $before) => /* some code */,
        fn (mixed $before) => /* some code */,
    )
    ->toConsole();
```

#### After

In some cases, you may need to perform certain actions after the benchmark loop has completed.

You can do this by calling the `after` method with a callback.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->after(fn () => /* some code */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

The loop name is passed to the callback as a parameter. You can use this information if needed.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->after(fn (int|string $name) => /* some code */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

#### AfterEach

In some cases, you may need to perform certain actions after each benchmark iteration.

You can do this by calling the `afterEach` method with a callback.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->afterEach(fn () => /* some code */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

The loop name and iteration number are passed to the callback as parameters.
You can use this information if needed.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->afterEach(fn (int|string $name, int $iteration) => /* some code */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

### Results

Use one of the following methods to obtain benchmark results.

#### toConsole

This method outputs the benchmark results to the console.

##### Option 1

```php
new Benchmark()
    ->round(2)
    ->compare([
        'foo' => static fn () => /* some code */,
        'bar' => static fn () => /* some code */,
    ])
    ->toConsole();
```

```Bash
+-------+---------------------+----------------------+
| #     | foo                 | bar                  |
+-------+---------------------+----------------------+
| min   | 14.56 ms - 0 bytes  | 14.62 ms - 0 bytes   |
| max   | 15.85 ms - 0 bytes  | 15.65 ms - 0 bytes   |
| avg   | 15.08 ms - 0 bytes  | 15.12 ms - 0 bytes   |
| total | 1206.7 ms - 0 bytes | 1209.44 ms - 0 bytes |
+-------+---------------------+----------------------+
| order | 1                   | 2                    |
+-------+---------------------+----------------------+
```

##### Option 2

```php
new Benchmark()
    ->round(2)
    ->compare([
        static fn () => /* some code */,
        static fn () => /* some code */,
    ])
    ->toConsole();
```

```bash
+-------+----------------------+----------------------+
| #     | 0                    | 1                    |
+-------+----------------------+----------------------+
| min   | 14.52 ms - 0 bytes   | 14.42 ms - 0 bytes   |
| max   | 15.78 ms - 0 bytes   | 15.7 ms - 0 bytes    |
| avg   | 15.09 ms - 0 bytes   | 15.01 ms - 0 bytes   |
| total | 1207.56 ms - 0 bytes | 1200.55 ms - 0 bytes |
+-------+----------------------+----------------------+
| order | 2                    | 1                    |
+-------+----------------------+----------------------+
```

##### Option 3

```php
new Benchmark()
    ->round(2)
    ->compare(
        static fn () => /* some code */,
        static fn () => /* some code */,
    )
    ->toConsole();
```

```bash
+-------+----------------------+----------------------+
| #     | 0                    | 1                    |
+-------+----------------------+----------------------+
| min   | 14.52 ms - 0 bytes   | 14.56 ms - 0 bytes   |
| max   | 15.68 ms - 0 bytes   | 15.61 ms - 0 bytes   |
| avg   | 15.1 ms - 0 bytes    | 15.04 ms - 0 bytes   |
| total | 1207.73 ms - 0 bytes | 1203.17 ms - 0 bytes |
+-------+----------------------+----------------------+
| order | 2                    | 1                    |
+-------+----------------------+----------------------+
```

##### Option 4

```php
new Benchmark()
    ->round(2)
    ->compare(
        foo: static fn () => /* some code */,
        bar: static fn () => /* some code */,
    )
    ->toConsole();
```

```bash
+-------+----------------------+----------------------+
| #     | foo                  | bar                  |
+-------+----------------------+----------------------+
| min   | 14.68 ms - 0 bytes   | 14.56 ms - 0 bytes   |
| max   | 15.69 ms - 0 bytes   | 15.64 ms - 0 bytes   |
| avg   | 15.13 ms - 0 bytes   | 15.07 ms - 0 bytes   |
| total | 1210.38 ms - 0 bytes | 1205.26 ms - 0 bytes |
+-------+----------------------+----------------------+
| order | 2                    | 1                    |
+-------+----------------------+----------------------+
```

##### Option 5: With Deviation Values

```php
new Benchmark()
    ->deviations(4)
    ->round(2)
    ->compare(
        foo: static fn () => /* some code */,
        bar: static fn () => /* some code */,
    )
    ->toConsole();
```

```bash
+------------------+-----------------------+---------------------+
| #                | 0                     | 1                   |
+------------------+-----------------------+---------------------+
| min              | 15.68 ms - 202 bytes  | 2.35 ms - 102 bytes |
| max              | 112.79 ms - 209 bytes | 9.76 ms - 109 bytes |
| avg              | 53.03 ms - 205 bytes  | 5.94 ms - 105 bytes |
| total            | 1696.81 ms - 6.42 KB  | 190.17 ms - 3.30 KB |
+------------------+-----------------------+---------------------+
| order            | 2                     | 1                   |
+------------------+-----------------------+---------------------+
| deviation time   | +0.100715             | +0.114023           |
| deviation memory | 0                     | 0                   |
+------------------+-----------------------+---------------------+
```

#### toData

This method returns benchmark results as an array of `DragonCode\Benchmark\Data\ResultData` DTO objects.

You can use it in your application for your own purposes.

```php
return new Benchmark()
    ->compare(
        foo: fn () => /* some code */,
        bar: fn () => /* some code */,
    )
    ->toData();
```

```bash
array:2 [
  "foo" => DragonCode\Benchmark\Data\ResultData {#17
    +min: DragonCode\Benchmark\Data\MetricData {#19
      +time: 14.6123
      +memory: 0.0
    }
    +max: DragonCode\Benchmark\Data\MetricData {#20
      +time: 15.7372
      +memory: 0.0
    }
    +avg: DragonCode\Benchmark\Data\MetricData {#21
      +time: 15.12268875
      +memory: 0.0
    }
    +total: DragonCode\Benchmark\Data\MetricData {#22
      +time: 1209.8151
      +memory: 0.0
    }
  }
  "bar" => DragonCode\Benchmark\Data\ResultData {#23
    +min: DragonCode\Benchmark\Data\MetricData {#24
      +time: 14.3369
      +memory: 0.0
    }
    +max: DragonCode\Benchmark\Data\MetricData {#25
      +time: 15.8259
      +memory: 0.0
    }
    +avg: DragonCode\Benchmark\Data\MetricData {#26
      +time: 15.10940625
      +memory: 0.0
    }
    +total: DragonCode\Benchmark\Data\MetricData {#27
      +time: 1208.7525
      +memory: 0.0
    }
  }
]
```

##### With Deviation Values

When calling the benchmark with deviation calculation, the DTO will contain the `deviations` property:

```php
return new Benchmark()
    ->deviations()
    ->compare(
        foo: fn () => /* some code */,
        bar: fn () => /* some code */,
    )
    ->toData();
```

```bash
array:2 [
  "foo" => DragonCode\Benchmark\Data\ResultData {#23
    +min: DragonCode\Benchmark\Data\MetricData {#64
      +time: 0.001
      +memory: 0.0
    }
    +max: DragonCode\Benchmark\Data\MetricData {#65
      +time: 0.0036
      +memory: 0.0
    }
    +avg: DragonCode\Benchmark\Data\MetricData {#66
      +time: 0.0024209375
      +memory: 0.0
    }
    +total: DragonCode\Benchmark\Data\MetricData {#67
      +time: 0.7747
      +memory: 0.0
    }
    +deviation: DragonCode\Benchmark\Data\DeviationData {#68
      +percent: DragonCode\Benchmark\Data\MetricData {#69
        +time: 0.0007048383984778
        +memory: 0.0
      }
    }
  }
  "bar" => DragonCode\Benchmark\Data\ResultData {#70
    +min: DragonCode\Benchmark\Data\MetricData {#71
      +time: 0.001
      +memory: 0.0
    }
    +max: DragonCode\Benchmark\Data\MetricData {#72
      +time: 0.0032
      +memory: 0.0
    }
    +avg: DragonCode\Benchmark\Data\MetricData {#73
      +time: 0.00242875
      +memory: 0.0
    }
    +total: DragonCode\Benchmark\Data\MetricData {#74
      +time: 0.7772
      +memory: 0.0
    }
    +deviation: DragonCode\Benchmark\Data\DeviationData {#75
      +percent: DragonCode\Benchmark\Data\MetricData {#76
        +time: 0.00061642429076895
        +memory: 0.0
      }
    }
  }
]
```

#### toAssert

This method allows you to validate benchmark results against expected values.

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->compare(/* ... */)
    ->toAssert()
    
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
    ->compare(/* ... */)
    ->toAssert()
    
    ->toBeMinTime(0.5)       // time must be greater than or equal to 0.5 ms
    ->toBeMaxTime(0.5)       // time must be greater than or equal to 0.5 ms
    ->toBeAvgTime(0.5)       // time must be greater than or equal to 0.5 ms
    ->toBeTotalTime(0.5)     // time must be greater than or equal to 0.5 ms
    
    ->toBeMinMemory(till: 1024)    // the memory footprint should not exceed 1024 bytes
    ->toBeMaxMemory(till: 1024)    // the memory footprint should not exceed 1024 bytes
    ->toBeAvgMemory(till: 1024)    // the memory footprint should not exceed 1024 bytes
    ->toBeTotalMemory(till: 4096); // the memory footprint should not exceed 4096 bytes
```

### Disable Progress Bar

In some cases, it is necessary to disable the progressbar so that it does not interfere in the console.

To do this, use the `disableProgressBar` method:

```PHP
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->disableProgressBar()
    // ...
```

## License

This package is licensed under the [MIT License](LICENSE).


[badge_build]:          https://img.shields.io/github/actions/workflow/status/TheDragonCode/benchmark/tests.yml?style=flat-square

[badge_downloads]:      https://img.shields.io/packagist/dt/dragon-code/benchmark.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/dragon-code/benchmark.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/TheDragonCode/benchmark?label=stable&style=flat-square

[link_build]:           https://github.com/TheDragonCode/benchmark/actions

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/dragon-code/benchmark
