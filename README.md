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

```bash
composer require dragon-code/benchmark --dev
```

## Usage

> [!NOTE]
>
> When more than 9 iterations are used, the top and bottom 10% of results are excluded from the average calculation,
> producing cleaner data less dependent on external factors.

```php
use function DragonCode\Benchmark\bench;

bench()
    ->compare(
        foo: fn () => /* some code */,
        bar: fn () => /* some code */,
    )
    ->toConsole();
```

You can use both the `bench()` helper function and the `Benchmark` class (`new Benchmark()` or `Benchmark::make()`).

Callbacks can be passed as an array or as arguments, with or without named keys:

```php
use DragonCode\Benchmark\Benchmark;

// As named arguments
new Benchmark()->compare(
    foo: fn () => /* some code */,
    bar: fn () => /* some code */,
)->toConsole();

// As an associative array
bench()->compare([
    'foo' => fn () => /* some code */,
    'bar' => fn () => /* some code */,
])->toConsole();
```

Example output:

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

### Iterations Count

By default, the benchmark performs 100 iterations per callback. Use the `iterations` method to change this.
The current iteration number is available as a callback parameter:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->iterations(5)
    ->compare(
        foo: fn (int $iteration) => /* some code */,
        bar: fn (int $iteration) => /* some code */,
    )
    ->toConsole();
```

### Round Precision

Use the `round` method to set the number of decimal places in console output:

```php
new Benchmark()
    ->round(2)
    ->compare(
        foo: fn () => /* some code */,
        bar: fn () => /* some code */,
    )
    ->toConsole();
```

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

Use the `deviations` method to measure the deviation between results. All loops will repeat the specified number of
times, and the output will include a `deviation` row:

```php
new Benchmark()
    ->deviations(4)
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

### Callbacks

You can register callbacks to run before/after the entire benchmark loop or before/after each iteration:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->before(fn (int|string $name) => /* once before all iterations of a callback */)
    ->beforeEach(fn (int|string $name, int $iteration) => /* before each iteration */)
    ->after(fn (int|string $name) => /* once after all iterations of a callback */)
    ->afterEach(fn (int|string $name, int $iteration) => /* after each iteration */)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    )
    ->toConsole();
```

The result of `beforeEach` is passed to the compare callback:

```php
new Benchmark()
    ->beforeEach(fn (int|string $name, int $iteration) => /* prepare data */)
    ->compare(
        fn (mixed $before) => /* use $before */,
        fn (mixed $before) => /* use $before */,
    )
    ->toConsole();
```

### Results

#### toConsole

Outputs results to the console:

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
| order | 2                    | 1                     |
+-------+----------------------+----------------------+
```

With deviation values:

```bash
+------------------+-----------------------+---------------------+
| #                | foo                   | bar                 |
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

Returns results as an array of `DragonCode\Benchmark\Data\ResultData` DTO objects:

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

Validates benchmark results against expected thresholds. Both `from` and `till` parameters are optional — use one or both:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->compare(/* ... */)
    ->toAssert()

    ->toBeMinTime(from: 0.5, till: 3)       // between 0.5 and 3 ms
    ->toBeMaxTime(from: 0.5, till: 3)       // between 0.5 and 3 ms
    ->toBeAvgTime(from: 0.5, till: 3)       // between 0.5 and 3 ms
    ->toBeTotalTime(from: 0.5, till: 9)     // between 0.5 and 9 ms

    ->toBeMinMemory(from: 0, till: 1024)    // between 0 and 1024 bytes
    ->toBeMaxMemory(from: 0, till: 1024)    // between 0 and 1024 bytes
    ->toBeAvgMemory(from: 0, till: 1024)    // between 0 and 1024 bytes
    ->toBeTotalMemory(from: 0, till: 4096)  // between 0 and 4096 bytes

    ->toBeDeviationTime(from: -0.5, till: 0.5)   // deviation between -0.5% and 0.5%
    ->toBeDeviationMemory(from: -2.5, till: 2.5); // deviation between -2.5% and 2.5%
```

### Snapshot Regression Testing

Snapshot regression testing allows you to detect performance regressions over time by comparing the current benchmark
results against a previously saved baseline (snapshot). On the **first run**, the results are saved to disk as
snapshots. On **subsequent runs**, the current results are compared against those snapshots and an `AssertionError` is
thrown if the regression exceeds the allowed threshold.

#### How Snapshots Work

- **First run:** snapshot files (`.snap`) do not exist yet, so no regression check is performed and the current
  results are saved to the configured directory.
- **Subsequent runs:** the current results are compared against the saved snapshots. If a regression exceeds the
  allowed `$max` percentage, an `AssertionError` is thrown.
- **Snapshot location:** each benchmark call stores its snapshots in a subdirectory derived from the source file
  path and line number, ensuring snapshots are unique per call site.

> [!NOTE]
>
> To reset a baseline, simply delete the corresponding `.snap` files. The next run will save fresh snapshots.


#### Configuring the Snapshot Directory

Use the `snapshots()` method to specify the directory where snapshot files will be stored.
By default, snapshots are stored in `./.benchmarks`:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->snapshots(directory: __DIR__ . '/.benchmarks')
    ->compare(
        foo: fn () => /* some code */,
        bar: fn () => /* some code */,
    )
    ->toAssert()
    ->toBeRegressionTime(max: 10)
    ->toBeRegressionMemory(max: 10);
```

> [!TIP]
>
> It is recommended to commit the generated snapshot files to your version control system so that regressions
> are detected consistently across different environments and CI runs.

#### toBeRegressionTime

Asserts that the execution time has not regressed by more than `$max` percent compared to the saved snapshot.

The `$max` parameter is specified as a percentage:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->snapshots(__DIR__ . '/.benchmarks')
    ->compare(
        foo: fn () => /* some code */,
        bar: fn () => /* some code */,
    )
    ->toAssert()
    ->toBeRegressionTime(max: 15); // allow up to 15% time regression
```

#### toBeRegressionMemory

Asserts that memory usage has not regressed by more than `$max` percent compared to the saved snapshot.

The `$max` parameter is specified as a percentage:

```php
use DragonCode\Benchmark\Benchmark;

new Benchmark()
    ->snapshots(__DIR__ . '/.benchmarks')
    ->compare(
        foo: fn () => /* some code */,
        bar: fn () => /* some code */,
    )
    ->toAssert()
    ->toBeRegressionMemory(max: 15); // allow up to 15% memory regression
```

### Disable Progress Bar

```php
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
