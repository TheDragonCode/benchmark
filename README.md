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
----- ------------------- ------------------- 
 #     0                   1                  
----- ------------------- ------------------- 
 1     0.011713027954102   0.015522003173828  
 2     0.014931917190552   0.015424013137817  
 3     0.015513896942139   0.014975070953369  
 4     0.015083789825439   0.014898061752319  
 5     0.014750003814697   0.014961004257202  
 6     0.015435934066772   0.015391111373901  
 7     0.015177965164185   0.014806985855103  
 8     0.014681816101074   0.01552677154541   
 9     0.014717102050781   0.015773057937622  
 10    0.015694856643677   0.014908075332642  
----- ------------------- ------------------- 
 min   0.011713027954102   0.014806985855103  
 max   0.015694856643677   0.015773057937622  
 avg   0.014770030975342   0.015218615531921  
----- ------------------- ------------------- 
       winner              loser              
----- ------------------- ------------------- 
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
----- ------------------- ------------------- 
 #     0                   1                  
----- ------------------- ------------------- 
 min   0.01220703125       0.014835119247437  
 max   0.015632152557373   0.015995979309082  
 avg   0.014835715293884   0.01535861492157   
----- ------------------- ------------------- 
       winner              loser              
----- ------------------- -------------------
```

### Round Precision

By default, the script does not round measurement results, but you can specify the number of decimal places to which rounding can be performed.

For example:

```php
use DragonCode\RuntimeComparison\Comparator;

(new Comparator())
    ->iterations(5)
    ->roundPrecision(4)
    ->compare(
        fn () => /* some code */,
        fn () => /* some code */,
    );
```

Result example:

```
----- -------- -------- 
 #     0        1       
----- -------- -------- 
 1     0.0112   0.015   
 2     0.0147   0.0155  
 3     0.0153   0.0153  
 4     0.0157   0.015   
 5     0.0154   0.0158  
----- -------- -------- 
 min   0.0112   0.015   
 max   0.0157   0.0158  
 avg   0.0144   0.0153  
----- -------- -------- 
       winner   loser   
----- -------- -------- 
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
