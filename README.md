# Runtime Comparison

![the dragon code runtime comparison](https://preview.dragon-code.pro/the-dragon-code/runtime-comparison.svg?brand=php)

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![Github Workflow Status][badge_build]][link_build]
[![License][badge_license]][link_license]

## Installation

To get the latest version of `Runtime Comparison`, simply require the project using [Composer](https://getcomposer.org):

```bash
composer require dragon-code/runtime-comparison --dev
```

Or manually update `require-dev` block of `composer.json` and run `composer update` console command:

```json
{
    "require-dev": {
        "dragon-code/runtime-comparison": "^1.0"
    }
}
```

## Using

> Note
>
> The result of the execution is printed to the console, so make sure you call the code from the console.

```php
use DragonCode\RuntimeComparison\Comparator;

(new Comparator())->compare(
    fn () => /* some code */,
    fn () => /* some code */,
);

(new Comparator())->compare([
    fn () => /* some code */,
    fn () => /* some code */,
]);

(new Comparator())->compare([
    'foo' => fn () => /* some code */,
    'bar' => fn () => /* some code */,
]);
```

Result example:

```
 ------- ------------- ------------- 
  #       0             1            
 ------- ------------- ------------- 
  1 ms    11.4014 ms    14.7829 ms   
  2 ms    15.2059 ms    15.3978 ms   
  3 ms    15.3926 ms    14.7081 ms   
  4 ms    15.4577 ms    15.2776 ms   
  5 ms    14.8991 ms    15.4104 ms   
  6 ms    15.6699 ms    15.4808 ms   
  7 ms    14.7774 ms    15.1976 ms   
  8 ms    15.5345 ms    15.5951 ms   
  9 ms    14.2978 ms    15.2521 ms   
  10 ms   14.6088 ms    15.4969 ms   
 ------- ------------- ------------- 
  min     11.4014 ms    14.7081 ms   
  max     15.6699 ms    15.5951 ms   
  avg     14.72451 ms   15.25993 ms  
 ------- ------------- ------------- 
  Order   - 1 -         - 2 -        
 ------- ------------- ------------- 
```

The time is specified in seconds rounded to the third decimal place.

### Iterations Count

By default, the comparator performs 10 iterations per callback, but you can change this number by calling the `iterations` method:

```php
use DragonCode\RuntimeComparison\Comparator;

(new Comparator())
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
use DragonCode\RuntimeComparison\Comparator;

(new Comparator())
    ->withoutData()
    ->compare([
        'foo' => fn () => /* some code */,
        'bar' => fn () => /* some code */,
    ]);
```

Result example:

```
 ------- ------------- ------------- 
  #       0             1            
 ------- ------------- ------------- 
  min     11.4234 ms    14.9415 ms   
  max     15.6337 ms    15.5312 ms   
  avg     14.57294 ms   15.20576 ms  
 ------- ------------- ------------- 
  Order   - 1 -         - 2 -        
 ------- ------------- ------------- 
```

> Note
>
> If the option to display detailed information is enabled (without using the `withoutData` method) and more than 1000 iterations are requested, then the output of detailed
> information will be forcibly disabled, since there will be absolutely no point in it with a significantly increasing load on the computer.

Result example:

```
 ------- ------------- ------------- 
  #       0             1            
 ------- ------------- ------------- 
  1 ms    11.4234 ms    15.1541 ms   
  2 ms    15.3612 ms    15.0497 ms   
  3 ms    15.2227 ms    15.5312 ms   
  4 ms    15.6337 ms    15.3523 ms   
  5 ms    15.2237 ms    14.9415 ms   
 ------- ------------- ------------- 
  min     11.4234 ms    14.9415 ms   
  max     15.6337 ms    15.5312 ms   
  avg     14.57294 ms   15.20576 ms  
 ------- ------------- ------------- 
  Order   - 1 -         - 2 -        
 ------- ------------- ------------- 
```

### Round Precision

By default, the script does not round measurement results, but you can specify the number of decimal places to which rounding can be performed.

For example:

```php
use DragonCode\RuntimeComparison\Comparator;

(new Comparator())
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
  1 ms    11.47 ms   15.28 ms  
  2 ms    14.89 ms   15.21 ms  
  3 ms    15.11 ms   15.13 ms  
  4 ms    15.62 ms   15.41 ms  
  5 ms    15.27 ms   14.99 ms  
 ------- ---------- ---------- 
  min     11.47 ms   14.99 ms  
  max     15.62 ms   15.41 ms  
  avg     14.47 ms   15.21 ms  
 ------- ---------- ---------- 
  Order   - 1 -      - 2 -     
 ------- ---------- ----------
```

## License

This package is licensed under the [MIT License](LICENSE).


[badge_build]:          https://img.shields.io/github/actions/workflow/status/TheDragonCode/runtime-comparison/phpunit.yml?style=flat-square

[badge_downloads]:      https://img.shields.io/packagist/dt/dragon-code/runtime-comparison.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/dragon-code/runtime-comparison.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/TheDragonCode/runtime-comparison?label=stable&style=flat-square

[badge_unstable]:       https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[link_build]:           https://github.com/TheDragonCode/runtime-comparison/actions

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/dragon-code/runtime-comparison
