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
  1 ms    12.1293 ms    15.2864 ms   
  2 ms    15.0713 ms    15.4331 ms   
  3 ms    15.2663 ms    14.6884 ms   
  4 ms    14.9315 ms    14.6972 ms   
  5 ms    14.5321 ms    15.0651 ms   
  6 ms    15.4832 ms    15.2158 ms   
  7 ms    15.9021 ms    14.6001 ms   
  8 ms    15.2042 ms    15.2389 ms   
  9 ms    14.0094 ms    14.7566 ms   
  10 ms   15.029 ms     15.3531 ms   
 ------- ------------- ------------- 
  min     12.1293 ms    14.6001 ms   
  max     15.9021 ms    15.4331 ms   
  avg     14.75584 ms   15.03347 ms  
 ------- ------------- ------------- 
          winner        loser        
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
 ------ ------------- ------------- 
  #      foo           bar       
 ------ ------------- ------------- 
  min    7.598 ms      14.665 ms    
  max    15.6512 ms    15.5594 ms   
  avg    13.62838 ms   15.21404 ms  
 ------ ------------- ------------- 
         winner        loser        
 ------ ------------- -------------
```

> Note
>
> If the option to display detailed information is enabled (without using the `withoutData` method) and more than 1000 iterations are requested, then the output of detailed
> information will be forcibly disabled, since there will be absolutely no point in it with a significantly increasing load on the computer.

Result example:

```
 ------ ------------- ------------- 
  #      0             1       
 ------ ------------- ------------- 
  1 ms   7.598 ms      14.665 ms    
  2 ms   15.323 ms     15.3995 ms   
  3 ms   15.6512 ms    15.0711 ms   
  4 ms   15.1066 ms    15.3752 ms   
  5 ms   14.4631 ms    15.5594 ms   
 ------ ------------- ------------- 
  min    7.598 ms      14.665 ms    
  max    15.6512 ms    15.5594 ms   
  avg    13.62838 ms   15.21404 ms  
 ------ ------------- ------------- 
         winner        loser        
 ------ ------------- -------------
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
