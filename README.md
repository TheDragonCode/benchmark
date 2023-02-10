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
 ------- -------------------- -------------------- 
  #       0                    1                   
 ------- -------------------- -------------------- 
  1       0.0087 ms - 0b       0.0014 ms - 0b      
  2       0.0037 ms - 0b       0.0012 ms - 0b      
  3       0.0014 ms - 0b       0.0011 ms - 0b      
  4       0.0012 ms - 0b       0.0011 ms - 0b      
  5       0.0012 ms - 0b       0.0012 ms - 0b      
  6       0.0011 ms - 0b       0.0011 ms - 0b      
  7       0.0012 ms - 0b       0.0012 ms - 0b      
  8       0.0013 ms - 0b       0.0011 ms - 0b      
  9       0.0011 ms - 0b       0.0015 ms - 0b      
                  ...   
  100     0.0011 ms - 0b       0.001 ms - 0b       
 ------- -------------------- -------------------- 
  min     0.001 ms - 0b        0.001 ms - 0b       
  max     0.0087 ms - 0b       0.0019 ms - 0b      
  avg     0.00126 ms - 0b      0.00127 ms - 0b     
  total   0.7841 ms - 50.4Kb   0.5959 ms - 45.8Kb  
 ------- -------------------- -------------------- 
  Order   - 1 -                - 2 -               
 ------- -------------------- -------------------- 
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

```
 ------- ------------------- ------------------- 
  #       0                   1                  
 ------- ------------------- ------------------- 
  1       0.0071 ms - 0b      0.0015 ms - 0b     
  2       0.0025 ms - 0b      0.0013 ms - 0b     
  3       0.0014 ms - 0b      0.0012 ms - 0b     
  4       0.0012 ms - 0b      0.0012 ms - 0b     
  5       0.0012 ms - 0b      0.0012 ms - 0b     
 ------- ------------------- ------------------- 
  min     0.0012 ms - 0b      0.0012 ms - 0b     
  max     0.0071 ms - 0b      0.0015 ms - 0b     
  avg     0.00268 ms - 0b     0.00128 ms - 0b    
  total   0.2473 ms - 6.8Kb   0.1038 ms - 2.3Kb  
 ------- ------------------- ------------------- 
  Order   - 2 -               - 1 -              
 ------- ------------------- ------------------- 
```

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
 ------- ------------------- ------------------- 
  #       foo                 bar                  
 ------- ------------------- ------------------- 
  min     0.0012 ms - 0b      0.0011 ms - 0b     
  max     0.0103 ms - 0b      0.0015 ms - 0b     
  avg     0.00344 ms - 0b     0.00118 ms - 0b    
  total   0.2837 ms - 6.8Kb   0.1064 ms - 2.3Kb  
 ------- ------------------- ------------------- 
  Order   - 2 -               - 1 -              
 ------- ------------------- ------------------- 
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
 ------- ------------------ ------------------ 
  #       0                  1                 
 ------- ------------------ ------------------ 
  1       11.85 ms - 0b      15.22 ms - 0b     
  2       14.56 ms - 0b      14.94 ms - 0b     
  3       15.4 ms - 0b       14.99 ms - 0b     
  4       15.3 ms - 0b       15.24 ms - 0b     
  5       14.76 ms - 0b      15.14 ms - 0b     
 ------- ------------------ ------------------ 
  min     11.85 ms - 0b      14.94 ms - 0b     
  max     15.4 ms - 0b       15.24 ms - 0b     
  avg     14.37 ms - 0b      15.11 ms - 0b     
  total   73.47 ms - 6.8Kb   76.03 ms - 2.3Kb  
 ------- ------------------ ------------------ 
  Order   - 1 -              - 2 -             
 ------- ------------------ ------------------ 
```

## Information

```
 ------- ------------------ ------------------ 
  #       foo                bar                 
 ------- ------------------ ------------------ 
  1       11.33 ms - 0b      14.46 ms - 0b     
  2       14.63 ms - 0b      14.8 ms - 0b      
  3       14.72 ms - 0b      15.02 ms - 0b     
  4       15.28 ms - 0b      15.04 ms - 0b     
  N+1     15.03 ms - 0b      15.09 ms - 0b     
 ------- ------------------ ------------------ 
  min     11.33 ms - 0b      14.46 ms - 0b     
  max     15.28 ms - 0b      15.09 ms - 0b     
  avg     14.2 ms - 0b       14.88 ms - 0b     
  total   71.62 ms - 6.8Kb   75.12 ms - 2.3Kb  
 ------- ------------------ ------------------ 
  Order   - 1 -              - 2 -             
 ------- ------------------ ------------------ 
```

* `foo`, `bar` - The names of the columns in the passed array. Needed for identification. By default, the array index is used, starting from zero. For example, `1, 2, 3,.. N+1`.
* `1`, `2`, `3`, ..., `N+1` - Verification iteration sequence number.
* `11.33 ms` - Execution time of the checked code in one iteration.
* `0b`, `6.8Kb`, etc. - The amount of RAM used by the checked code.
* `min` - Minimum code processing time.
* `max` - Maximum code processing time.
* `avg` - The arithmetic mean value among all iterations, taking into account the elimination of 10% of the smallest and 10% of the largest values to obtain a more accurate value
  through the possible intervention of external factors.
* `total` - The total time and RAM spent on checking all iterations of the code.

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
