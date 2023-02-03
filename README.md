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
    fn () => sleep(1),
    fn () => sleep(1),
);

(new Comparator())->compare([
    fn () => sleep(1),
    fn () => sleep(1),
]);

(new Comparator())->compare([
    'foo' => fn () => sleep(1),
    'bar' => fn () => sleep(1),
]);
```

Result example:

```
----- -------- -------- 
 #     foo      bar  
----- -------- -------- 
 1     0.014    0.016   
 2     0.015    0.016   
 3     0.015    0.016   
 4     0.015    0.015   
 5     0.015    0.015   
----- -------- -------- 
 min   0.014    0.015   
 max   0.015    0.016   
 avg   0.015    0.016   
----- -------- -------- 
       winner   loser   
----- -------- -------- 
```

The time is specified in seconds rounded to the third decimal place.

### Iterations Count

By default, the comparator performs 10 iterations per callback, but you can change this number by calling the `iterations` method:

```php
use DragonCode\RuntimeComparison\Comparator;

(new Comparator())
    ->iterations(20)
    ->compare(
        fn () => sleep(1),
        fn () => sleep(1),
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
        'foo' => fn () => sleep(1),
        'bar' => fn () => sleep(1),
    ]);
```

Result example:

```
----- -------- ------- 
 #     foo      bar
----- -------- ------- 
 min   0.011    0.015  
 max   0.015    0.016  
 avg   0.014    0.015  
----- -------- ------- 
       winner   loser  
----- -------- -------
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
