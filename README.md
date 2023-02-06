# Benchmark

![the dragon code benchmark](https://preview.dragon-code.pro/the-dragon-code/benchmark.svg?brand=php)

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![Github Workflow Status][badge_build]][link_build]
[![License][badge_license]][link_license]

## Installation

To get the latest version of `The Dragon Code: Benchmark`, simply require the project using [Composer](https://getcomposer.org):

```bash
composer require dragon-code/benchmark --dev
```

Or manually update `require-dev` block of `composer.json` and run `composer update` console command:

```json
{
    "require-dev": {
        "dragon-code/benchmark": "^2.0"
    }
}
```

## Using

> Note
>
> The result of the execution is printed to the console, so make sure you call the code from the console.

```php
use DragonCode\Benchmark\Benchmark;

(new Benchmark())->compare(
    fn () => /* some code */,
    fn () => /* some code */,
);

(new Benchmark())->compare([
    fn () => /* some code */,
    fn () => /* some code */,
]);

(new Benchmark())->compare([
    'foo' => fn () => /* some code */,
    'bar' => fn () => /* some code */,
]);
```

Result example:

```
 ------- ------------- ------------- 
  #       0             1            
 ------- ------------- ------------- 
  1       11.3845 ms    15.565 ms    
  2       14.92 ms      14.8241 ms   
  3       14.812 ms     15.2948 ms   
  4       15.3211 ms    14.9243 ms   
  5       15.045 ms     15.4147 ms   
  6       15.3918 ms    15.1484 ms   
  7       14.6678 ms    14.0797 ms   
  8       14.7602 ms    14.613 ms    
  9       14.9372 ms    15.6712 ms   
               ...   
  100     15.2036 ms    14.6706 ms   
 ------- ------------- ------------- 
  min     11.3845 ms    14.0797 ms   
  max     15.3918 ms    15.6712 ms   
  avg     14.64432 ms   15.02058 ms  
 ------- ------------- ------------- 
  Order   - 1 -         - 2 -        
 ------- ------------- ------------- 
```

When measuring the average value among the results, when more than 10 iterations are used, the final data is filtered by peak values. The calculation of the 10% of the lowest and
10% of the highest values is excluded from the total result, thus the final data becomes cleaner and less dependent on any external factors.

### Iterations Count

By default, the benchmark performs 100 iterations per callback, but you can change this number by calling the `iterations` method:

```php
use DragonCode\Benchmark\Benchmark;

(new Benchmark())
    ->iterations(5)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    );
```

If the passed value is less than 1, then one iteration will be performed for each callback.

### Without Data

If you want to see only the summary result of the run time without detailed information for each iteration, then you can call the `withoutData` method, which will display only the
summary information:

```php
use DragonCode\Benchmark\Benchmark;

(new Benchmark())
    ->withoutData()
    ->compare([
        'foo' => fn () => /* some code */,
        'bar' => fn () => /* some code */,
    ]);
```

Result example:

```
 ------- ------------- ------------- 
  #       foo           bar            
 ------- ------------- ------------- 
  min     11.3845 ms    14.0797 ms   
  max     15.3918 ms    15.6712 ms   
  avg     14.64432 ms   15.02058 ms  
 ------- ------------- ------------- 
  Order   - 1 -         - 2 -        
 ------- ------------- ------------- 
```

> Note
>
> If the option to display detailed information is enabled (without using the `withoutData` method) and more than 1000 iterations are requested, then the output of detailed
> information will be forcibly disabled, since there will be absolutely no point in it with a significantly increasing load on the computer.

### Round Precision

By default, the script does not round measurement results, but you can specify the number of decimal places to which rounding can be performed.

For example:

```php
use DragonCode\Benchmark\Benchmark;

(new Benchmark())
    ->iterations(5)
    ->round(2)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    );
```

Result example:

```
 ------- ---------- ---------- 
  #       0          1         
 ------- ---------- ---------- 
  1       12.22 ms   14.65 ms  
  2       14.54 ms   15.37 ms  
  3       15.26 ms   14.37 ms  
  4       15.07 ms   14.73 ms  
  5       14.67 ms   14.74 ms  
 ------- ---------- ---------- 
  min     12.22 ms   14.37 ms  
  max     15.26 ms   15.37 ms  
  avg     14.35 ms   14.77 ms  
 ------- ---------- ---------- 
  Order   - 1 -      - 2 -     
 ------- ---------- ---------- 
```

## License

This package is licensed under the [MIT License](LICENSE).


[badge_build]:          https://img.shields.io/github/actions/workflow/status/TheDragonCode/benchmark/phpunit.yml?style=flat-square

[badge_downloads]:      https://img.shields.io/packagist/dt/dragon-code/benchmark.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/dragon-code/benchmark.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/TheDragonCode/benchmark?label=stable&style=flat-square

[badge_unstable]:       https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[link_build]:           https://github.com/TheDragonCode/benchmark/actions

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/dragon-code/benchmark
